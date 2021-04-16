(function ($) {
    let
        settings = {
            // If set bookmarks are stored locally in localStorage
            storeLocal: 10,
            // Time in seconds during which the bookmarks are valid hence not queried from server
            localStorageTTL: 13600
        },
        // Bookmark from current page
        bookmark = {};
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
            if (parseInt(settings.storeLocal)) {
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
                return ((Date.now() - timestamp) / 1000) > settings.localStorageTTL;
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
            $('#bookmarks-list').html(ajaxResult.list);

            if (ajaxResult.isBookmarked) {
                $('.bookmark-this-page').addClass('is-bookmarked');
            } else {
                $('.bookmark-this-page').removeClass('is-bookmarked');
            }

            if (typeof ajaxResult.localBookmarks === 'object') {
                BookmarkStorage.list = ajaxResult.localBookmarks;
            }
        }
        static ajax(url, data = {}) {
            data = {...data, 'tx_bookmarkpages_bookmarks[localBookmarks]': BookmarkStorage.list}
            $.ajax({
                url: url,
                type: 'post',
                data: data
            }).done(BookmarksAssistant.listQueryHandler);
        }
        static initListFromStorage() {
            let bookmarks = BookmarkStorage.list,
                $bookmarksList = $('#bookmarks-list'),
                $listItem = $($('#bookmark-template').html().trim());
            $bookmarksList.empty();
            for (const bookmark of bookmarks) {
                let $item = $listItem.clone();
                $('.bookmark-link', $item)
                    .attr('title', bookmark.title)
                    .attr('href', bookmark.url)
                    .val(bookmark.title);
                $('.bookmark-ajax-submit', $item).data('remove', bookmark.id)
                $bookmarksList.append($item);
            }
        }
    }

    let bookmarks = new class {
        init() {
            if (settings.storeLocal && !BookmarkStorage.isOutdated) {
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
                bookmarks.remove($('#bookmarks').data('id'))
            } else if (removeID) {
                bookmarks.remove(removeID)
            }
        });

        // Initialise the bookmarks
        Object.assign(settings, $('#bookmarks').data('settings'));
        bookmark = $('#bookmarks').data('bookmark');
        bookmarks.init();
    });
})(jQuery);
