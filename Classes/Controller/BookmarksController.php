<?php

/*
 * This file is part of the package buepro/bookmark_pages.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Colorcube\BookmarkPages\Controller;

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
        $bookmark = Bookmark::createFromCurrent();
        $this->view->assignMultiple([
            'bookmark' => $bookmark->toArray()
        ]);
    }

    /**
     * Adds the current page as bookmark and renders/returns updated list as html
     *
     * This is meant to be called by ajax (typoscript_rendering)
     *
     * @param array $localBookmarks
     */
    public function bookmarkAction($localBookmarks = [])
    {
        // use the parameter directly and ignore chash because url is submitted by JS
        $url = GeneralUtility::_GP('url');
        $url = $url ? $url : null;

        $bookmark = Bookmark::createFromCurrent($url);

        $bookmarks = new Bookmarks();
        $bookmarks->merge($localBookmarks);
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
     * @param array $localBookmarks
     */
    public function deleteAction($id = '', $localBookmarks = [])
    {
        $bookmarks = new Bookmarks();
        $bookmarks->merge($localBookmarks);
        if ($id) {
            $bookmarks->removeBookmark($id);
            $bookmarks->persist();
        }
        $this->updateAndSendList($bookmarks);
    }

    /**
     * Action to get bookmark list
     *
     * @param array $localBookmarks
     */
    public function listEntriesAction($localBookmarks = [])
    {
        $bookmarks = new Bookmarks();
        $bookmarks->merge($localBookmarks);
        $this->updateAndSendList($bookmarks);
    }

    /**
     * This is for ajax requests
     *
     * @param Bookmarks $bookmarks
     */
    public function updateAndSendList(Bookmarks $bookmarks)
    {
        // check if we bookmarked the current page
        $bookmark = Bookmark::createFromCurrent();
        $isBookmarked = $bookmarks->bookmarkExists($bookmark);

        // build the ajax response data
        $response = [
            'isBookmarked' => $isBookmarked,
            'bookmarks' => $bookmarks->getBookmarksForLocalStorage()
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
}
