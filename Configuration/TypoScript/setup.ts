plugin.tx_bookmarkpages {
	view {
		templateRootPath = {$plugin.tx_bookmarkpages.view.templateRootPath}
		partialRootPath = {$plugin.tx_bookmarkpages.view.partialRootPath}
		layoutRootPath = {$plugin.tx_bookmarkpages.view.layoutRootPath}
	}
}

page.includeJSFooterlibs {
	bookmark_pages_jquery = https://code.jquery.com/jquery-2.2.4.min.js
	bookmark_pages_jquery {
		excludeFromConcatenation = 1
		disableCompression = 1
		external = 1
	}
}

page.includeJSFooter.bookmark_pages = EXT:bookmark_pages/Resources/Public/Scripts/JavaScript/bookmark_pages.js
