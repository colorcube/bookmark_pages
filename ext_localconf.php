<?php
defined('TYPO3') or die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Colorcube.' . $_EXTKEY,
    'Bookmarks',
    array(
        'Bookmarks' => 'index, bookmark, delete',
    ),
    // non-cacheable actions
    array(
        'Bookmarks' => 'index, bookmark, delete',
    )
);

