<?php
defined('TYPO3_MODE') or die();


/**
 * add TypoScript to template record
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('bookmark_pages', 'Configuration/TypoScript/', 'Bookmark Pages');
