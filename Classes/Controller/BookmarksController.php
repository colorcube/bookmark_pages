<?php
namespace Colorcube\BookmarkPages\Controller;

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

use Colorcube\BookmarkPages\Model\Bookmark;
use Colorcube\BookmarkPages\Model\Bookmarks;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Plugin controller
 */
class BookmarksController extends ActionController
{

    /**
     * display bookmarks list
     */
    public function indexAction()
    {
        $bookmarks = new Bookmarks();
        $bookmark = Bookmark::createFromCurrent();
        $this->view->assignMultiple([
            'bookmarks' => $bookmarks->getBookmarks(),
            'isBookmarked' => $bookmarks->bookmarkExists($bookmark),
            'id' => $bookmark->getId()
        ]);
    }

    /**
     * Adds the current page as bookmark and renders/returns updated list as html
     *
     * This is meant to be called by ajax (typoscript_rendering)
     */
    public function bookmarkAction()
    {
        // use the parameter directly and ignore chash because url is submitted by JS
        $url = GeneralUtility::_GP('url');
        $url = $url ? $url : null;

        $bookmark = Bookmark::createFromCurrent($url);

        $bookmarks = new Bookmarks();
        $bookmarks->addBookmark($bookmark);
        $bookmarks->persist();

        $this->updateAndSendList($bookmarks);
    }

    /**
     * Remove a bookmark from list and renders/returns updated list as html
     *
     * This is meant to be called by ajax (typoscript_rendering)
     *
     * @param string $id
     */
    public function deleteAction($id = '')
    {
        $bookmarks = new Bookmarks();
        if ($id) {
            $bookmarks->removeBookmark($id);
            $bookmarks->persist();
        }
        $this->updateAndSendList($bookmarks);
    }

    /**
     * Action to get bookmark list
     *
     * @param array $clientBookmarks
     */
    public function listEntriesAction($clientBookmarks = [])
    {
        $bookmarks = new Bookmarks();
        $this->updateAndSendList($bookmarks, $clientBookmarks);
    }

    /**
     * This is for ajax requests
     *
     * @param Bookmarks $bookmarks
     * @param array $clientBookmarks
     */
    public function updateAndSendList(Bookmarks $bookmarks, array $clientBookmarks = [])
    {
        // build the html for the response
        $this->view->assign('bookmarks', $bookmarks->getBookmarks());
        $listHtml = $this->view->render();


        // check if we bookmarked the current page
        $bookmark = Bookmark::createFromCurrent();
        $isBookmarked = $bookmarks->bookmarkExists($bookmark);


        // get data for local storage
        $localBookmarks = $bookmarks->getLocalBookmarks();


        // build the ajax response data
        $response = [
            'isBookmarked' => $isBookmarked,
            'list' => $listHtml,
            'localBookmarks' => $localBookmarks,
            'clientBookmarks' => $clientBookmarks
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

}
