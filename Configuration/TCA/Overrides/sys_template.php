<?php
defined('TYPO3') || die('Access denied.');


/**
 * add TypoScript to template record
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('bookmark_pages', 'Configuration/TypoScript/', 'Bookmark Pages');
