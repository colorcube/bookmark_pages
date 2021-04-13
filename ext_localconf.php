<?php
defined('TYPO3') or die('Access denied.');

(function () {
    $version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version();
    $version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger($version);
    if ($version < 10000000) {
        // For TYPO3 < V10
        // @extensionScannerIgnoreLine
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Colorcube.BookmarkPages',
            'Bookmarks',
            array(
                'Bookmarks' => 'index, bookmark, delete',
            ),
            // non-cacheable actions
            array(
                'Bookmarks' => 'index, bookmark, delete',
            )
        );
    } else {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'BookmarkPages',
            'Bookmarks',
            [
                \Colorcube\BookmarkPages\Controller\BookmarksController::class => 'index, bookmark, delete'
            ],
            [
                \Colorcube\BookmarkPages\Controller\BookmarksController::class => 'index, bookmark, delete'
            ]
        );
    }
})();