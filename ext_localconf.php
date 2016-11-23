<?php
defined('TYPO3_MODE') or die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Colorcube.' . $_EXTKEY,
    'Bookmarks',
    array(
        'Bookmarks' => 'index, greet, delete',
    ),
    // non-cacheable actions
    array(
        'Bookmarks' => 'index, greet, delete',
    )
);

