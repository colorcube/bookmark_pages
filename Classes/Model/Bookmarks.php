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

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Provide access to a list of Bookmark
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
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
        return $this->bookmarks;
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
        $this->bookmarks[$bookmark->id] = $bookmark;
        $this->changeFlag = true;
    }


    /**
     * Get Bookmark by given id
     *
     * @param $id
     * @return Bookmark|mixed
     */
    public function getBookmark($id)
    {
        return $this->bookmarks[$id];
    }


    /**
     * Remove bookmark by given id
     *
     * @param $id
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
             * (And dooon't tell me json would be a good idea)
             */
            $bookMarksXml = GeneralUtility::array2xml($bookmarks);

            $this->getDatabaseConnection()->exec_UPDATEquery(
                $this->getUser()->user_table,
                $this->getUser()->userid_column . '=' . (int)$this->getUser()->user[$this->getUser()->userid_column],
                [self::BOOKMARKS_COLUMN => $bookMarksXml]);
            $this->changeFlag = false;
        }
    }


    /**
     * Get global frontend user
     * @return FrontendUserAuthentication
     */
    protected function getUser()
    {
        return $GLOBALS["TSFE"]->fe_user;
    }


    /**
     * Get global database connection
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}