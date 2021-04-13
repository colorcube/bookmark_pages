<?php
defined('TYPO3') || die('Access denied.');

/**
 * Register Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'bookmark_pages',
    'Bookmarks',
    'Bookmark Pages'
);

$pluginSignature = 'bookmarkpages_bookmarks';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'recursive,select_key,pages';

