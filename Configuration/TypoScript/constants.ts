plugin.tx_bookmarkpages {
    view {
        # cat=plugin.tx_bookmarkpages/file; type=string; label=Path to template root (FE)
        templateRootPath = EXT:bookmark_pages/Resources/Private/Templates/
        # cat=plugin.tx_bookmarkpages/file; type=string; label=Path to template partials (FE)
        partialRootPath = EXT:bookmark_pages/Resources/Private/Partials/
        # cat=plugin.tx_bookmarkpages/file; type=string; label=Path to template layouts (FE)
        layoutRootPath = EXT:bookmark_pages/Resources/Private/Layouts/
    }
    settings {
        # cat=plugin.tx_bookmarkpages/general; type=boolean; label=Store bookmarks local:If set the bookmarks will be stored in the local storage from the clients browser.
        storeLocal = 0
        # cat=plugin.tx_bookmarkpages/general; type=int; label=Local storage TTL (s):Time in seconds bookmarks might be read from the browsers local storage before they get queried from the server. Just used when storeLocal is set.
        localStorageTTL = 3600
    }
}
