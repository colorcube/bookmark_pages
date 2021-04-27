<?php

/*
 * This file is part of the package buepro/bookmark_pages.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

/**
 * add TypoScript to template record
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('bookmark_pages', 'Configuration/TypoScript/', 'Bookmark Pages');
