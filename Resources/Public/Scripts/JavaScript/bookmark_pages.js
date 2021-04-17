(function ($) {
    let
        // Bookmark from current page
        bookmark = {};
    class Settings {
        // If set bookmarks are stored locally in localStorage
        _storeLocal = false;
        // Time in seconds during which the bookmarks are valid hence not queried from server
        _localStorageTTL = 3600;
        static init (settings) {
            if (typeof settings !== 'object') {
                return;
            }
            if (settings.storeLocal !== 'undefined') {
                self._storeLocal = Boolean(parseInt(settings.storeLocal));
            }
            if (settings.localStorageTTL !== 'undefined') {
                self._localStorageTTL = parseInt(settings.localStorageTTL);
            }
        }
        static get storeLocal() {
            return self._storeLocal;
        }
        static get localStorageTTL() {
            return self._localStorageTTL
        }
    }
    class BookmarkStorage {
        /**
         * @return JSON object from bookmarks list held in local storage from browser
         */
        static get list() {
            return JSON.parse(localStorage.getItem('txBookmarkPagesBookmarks'));
        }

        /**
         * @param bookmarks Array from bookmarks
         */
        static set list(bookmarks) {
            if (Settings.storeLocal) {
                localStorage.setItem('txBookmarkPagesBookmarks', JSON.stringify(bookmarks));
                localStorage.setItem('txBookmarkPagesTimestamp', Date.now());
            }
        }

        /**
         * @return {boolean}
         */
        static get isOutdated() {
            let timestamp = localStorage.getItem('txBookmarkPagesTimestamp');
            if (timestamp) {
                return ((Date.now() - timestamp) / 1000) > Settings.localStorageTTL;
            }
            return true;
        }
    }

    class BookmarksAssistant {
        /**
         * Ajax callback function to update the bookmarks list and the links to bookmark a page.
         *
         * @param ajaxResult Object with properties `list` and `isBookmarked`
         */
        static listQueryHandler(ajaxResult) {
            // @todo validate ajaxResult.bookmarks
            $('#bookmarks-list').html(BookmarksAssistant.initList(ajaxResult.bookmarks));

            if (ajaxResult.isBookmarked) {
                $('.bookmark-this-page').addClass('is-bookmarked');
            } else {
                $('.bookmark-this-page').removeClass('is-bookmarked');
            }

            BookmarkStorage.list = ajaxResult.bookmarks;
        }
        static ajax(url, data = {}) {
            data = {...data, 'tx_bookmarkpages_bookmarks[localBookmarks]': BookmarkStorage.list}
            $.ajax({
                url: url,
                type: 'post',
                data: data
            }).done(BookmarksAssistant.listQueryHandler);
        }
        static initList(bookmarks) {
            let $bookmarksList = $('#bookmarks-list'),
                $listItem = $($('#bookmark-template').html().trim());
            $bookmarksList.empty();
            for (const bookmark of bookmarks) {
                let $item = $listItem.clone();
                $('.bookmark-link', $item)
                    .attr('title', bookmark.title)
                    .attr('href', bookmark.url)
                    .text(bookmark.title);
                $('.bookmark-ajax-submit', $item).data('remove', bookmark.id)
                $bookmarksList.append($item);
            }
        }
        static initListFromStorage() {
            let bookmarks = BookmarkStorage.list;
            // @todo validate bookmarks
            BookmarksAssistant.initList(bookmarks);
        }
    }

    let bookmarks = new class {
        init() {
            if (Settings.storeLocal && !BookmarkStorage.isOutdated) {
                BookmarksAssistant.initListFromStorage();
            } else {
                BookmarksAssistant.ajax($('#bookmarks').data('update-ajaxuri'));
            }
        }
        add() {
            BookmarksAssistant.ajax($('#bookmarks').data('add-ajaxuri'));
        }
        remove(removeID) {
            BookmarksAssistant.ajax(
                $('#bookmarks').data('remove-ajaxuri'),
                {'tx_bookmarkpages_bookmarks[id]': removeID}
            );
        }
    }

    $(function () {
        // doing it this way:
        // $('.bookmark-ajax-submit').on('click', function (event) {
        // would not work for initially hidden elements
        $('.bookmark-pages').on('click', '.bookmark-ajax-submit', function (event) {
            event.preventDefault();
            let $this = $(this),
                removeID = $(this).data('remove');
            if ($this.hasClass('bookmark-this-page')){
                bookmarks.add();
            } else if ($this.hasClass('remove-this-page')) {
                bookmarks.remove(bookmark.id)
            } else if (removeID) {
                bookmarks.remove(removeID)
            }
        });

        // Initialise the bookmarks
        Settings.init($('#bookmarks').data('settings'));
        bookmark = $('#bookmarks').data('bookmark');
        bookmarks.init();
    });
})(jQuery);
