<?php
defined('TYPO3_MODE') or die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'PiExample',
    'Bookmark Pages'
);
