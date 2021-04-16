<?php
namespace Colorcube\BookmarkPages\Model;

/*
 * This file is part of the Bookmark Pages TYPO3 extension.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read
 * LICENSE file that was distributed with this source code.
 */


use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * Provide access to a list of Bookmark
 */
class Bookmarks {

    /**
     * column in db
     */
    const BOOKMARKS_COLUMN =  'tx_bookmarks_pages';

    /**
     * @var array|Bookmark[]
     */
    protected $bookmarks = [];

    /**
     * @var bool Flag if bookmarks has changed an need to be saved
     */
    protected $changeFlag = false;

    /**
     * Initialize bookmarks
     */
    public function __construct()
    {
        // is login user?
        if (is_array($this->getUser()->user) && $this->getUser()->user[$this->getUser()->userid_column]) {

            $bookmarks  = $this->getUser()->user[self::BOOKMARKS_COLUMN];
            $bookmarks = (array)GeneralUtility::xml2array($bookmarks);
            foreach ($bookmarks as $bookmark) {
                if (isset($bookmark['id'])) {
                    $this->bookmarks[$bookmark['id']] = new Bookmark($bookmark);
                }
            }
        }
    }


    /**
     * persist bookmarks if needed
     */
    public function __destruct() {
        $this->persist();
    }


    /**
     * Get all Bookmarks
     *
     * @return array|Bookmark[]
     */
    public function getBookmarks()
    {
        return (array)$this->bookmarks;
    }


    /**
     * clear all bookmarks
     */
    public function clearBookmarks()
    {
        $this->bookmarks = [];
        $this->changeFlag = true;
    }


    /**
     * Add a bookmark
     *
     * @param Bookmark $bookmark
     */
    public function addBookmark(Bookmark $bookmark)
    {
        $this->bookmarks[$bookmark->getId()] = $bookmark;
        $this->changeFlag = true;
    }


    /**
     * Get Bookmark by given id
     *
     * @param string $id
     * @return Bookmark|mixed
     */
    public function getBookmark($id)
    {
        return $this->bookmarks[$id];
    }


    /**
     * Check if a given bookmark is stored already
     *
     * @param Bookmark $bookmark
     * @return boolean
     */
    public function bookmarkExists(Bookmark $bookmark)
    {
        return isset($this->bookmarks[$bookmark->getId()]);
    }


    /**
     * Remove bookmark by given id
     *
     * @param string $id
     */
    public function removeBookmark($id)
    {
        unset($this->bookmarks[$id]);
        $this->changeFlag = true;
    }


    /**
     * persist bookmarks if needed
     */
    public function persist()
    {
        if ($this->changeFlag && is_array($this->getUser()->user) && $this->getUser()->user[$this->getUser()->userid_column]) {
            $bookmarks = [];
            foreach ($this->bookmarks as $bookmark) {
                    $bookmarks[] = $bookmark->toArray();
            }
            /*
             * Why xml?
             *
             * Why not! You can even process it in the db if you like
             * (And dooon't tell me json would be a good idea, or serialized php ... haaahaaaaaa)
             */
            $bookMarksXml = GeneralUtility::array2xml($bookmarks);

            /** @var  QueryBuilder $queryBuilder */
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable($this->getUser()->user_table);
            $queryBuilder
                ->update($this->getUser()->user_table)
                ->where(
                    $queryBuilder->expr()->eq(
                        $this->getUser()->userid_column,
                        $queryBuilder->createNamedParameter((int)$this->getUser()->user[$this->getUser()->userid_column], \PDO::PARAM_INT)
                    )
                )
                ->set(self::BOOKMARKS_COLUMN, $bookMarksXml)
                ->execute();

            $this->changeFlag = false;
        }
    }


    /**
     * Get global frontend user
     * @return \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
     */
    protected function getUser()
    {
        return $GLOBALS["TSFE"]->fe_user;
    }


    /**
     * Get bookmarks for local storage in browser
     */
    public function getLocalBookmarks(): array
    {
        $result = [];
        foreach($this->bookmarks as $bookmark) {
            $result[] = $bookmark->toArray();
        }
        return $result;
    }
}
