plugin.tx_bookmarkpages {
    view {
        templateRootPaths.10 = {$plugin.tx_bookmarkpages.view.templateRootPath}
        partialRootPaths.10 = {$plugin.tx_bookmarkpages.view.partialRootPath}
        layoutRootPaths.10 = {$plugin.tx_bookmarkpages.view.layoutRootPath}
    }
    settings {
        storeLocal = {$plugin.tx_bookmarkpages.settings.storeLocal}
        localStorageTTL = {$plugin.tx_bookmarkpages.settings.localStorageTTL}
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

tt_content.login.stdWrap.postCObject = TEXT
tt_content.login.stdWrap.postCObject.value (
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function(event) {
        let button = document.querySelector('.frame-type-login [type="submit"]');
        if (button) {
            button.addEventListener('click', function () {
                localStorage.setItem('txBookmarkPagesReload', '1');
            });
        }
    });
</script>
)
