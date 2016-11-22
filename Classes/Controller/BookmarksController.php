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
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('name', 'Jarvis');
    }

    /**
     * Action greeting
     *
     * @param string $name
     *
     * @return void
     */
    public function greetAction($name)
    {
        $this->view->assign('name', $name);
    }
}
