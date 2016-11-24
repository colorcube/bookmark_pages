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
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class BookmarksController extends ActionController
{

    /**
     * display bookmarks list
     */
    public function indexAction()
    {
        $bookmarks = new Bookmarks();

        // testing
        // $bookmark = new Bookmark('http://www.google.de', 'Google', $pid=null, $parameter=null);
        // $bookmarks->addBookmark($bookmark);
        
        // $bookmark = new Bookmark('', 'One', 1, 'abc');
        // $bookmarks->addBookmark($bookmark);
        
        // $bookmark = Bookmark::createFromCurrent();
        // $bookmarks->addBookmark($bookmark);
        // $bookmarks->persist();
        
        $this->view->assign('bookmarks', $bookmarks->getBookmarks());
    }

    /**
     * Adds the current page as bookmark and renders/returns updated list as html
     *
     * This is meant to be called by ajax (typoscript_rendering)
     */
    public function bookmarkAction()
    {
        $bookmark = Bookmark::createFromCurrent();

        $bookmarks = new Bookmarks();
        $bookmarks->addBookmark($bookmark);
        $bookmarks->persist();

        $this->view->assign('bookmarks', $bookmarks->getBookmarks());
    }

    /**
     * Remove a bookmark from list and renders/returns updated list as html
     *
     * This is meant to be called by ajax (typoscript_rendering)
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $bookmarks = new Bookmarks();
        $bookmarks->removeBookmark($id);
        $bookmarks->persist();
        $this->view->assign('bookmarks', $bookmarks->getBookmarks());
    }

}
