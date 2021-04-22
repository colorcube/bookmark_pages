<?php
defined('TYPO3') || die('Access denied.');

(function () {
    $version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version();
    $version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger($version);
    /**
     * Register Plugin
     */
    if ($version < 10000000) {
        // For TYPO3 < V10
        // @extensionScannerIgnoreLine
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Colorcube.BookmarkPages',
            'Bookmarks',
            'Bookmark Pages'
        );
    } else {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'BookmarkPages',
            'Bookmarks',
            'Bookmark Pages'
        );
    }

    /**
     * Add flexForm
     */
    $pluginSignature = 'bookmarkpages_bookmarks';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature,
        'FILE:EXT:bookmark_pages/Configuration/FlexForms/Bookmarks.xml'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'recursive,select_key,pages';
})();
