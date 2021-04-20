(function ($) {
    const settings = {
        // If set bookmarks are stored locally in localStorage
        _storeLocal: false,
        // Time in seconds during which the bookmarks are valid hence not queried from server
        _localStorageTTL: 3600,
        init () {
            // Assign settings defined by host
            let settings = $('#bookmarks').data('settings');
            if (typeof settings !== 'object') {
                return;
            }
            if (settings.storeLocal !== 'undefined') {
                this._storeLocal = Boolean(parseInt(settings.storeLocal));
            }
            if (settings.localStorageTTL !== 'undefined') {
                this._localStorageTTL = parseInt(settings.localStorageTTL);
            }
        },
        get storeLocal() {
            return this._storeLocal;
        },
        get localStorageTTL() {
            return this._localStorageTTL
        }
    }

    const storage = {
        /**
         * @return JSON object from bookmarks list held in local storage from browser
         */
        get list() {
            return JSON.parse(localStorage.getItem('txBookmarkPagesBookmarks'));
        },

        /**
         * @param bookmarks Array from bookmarks
         */
        set list(bookmarks) {
            if (settings.storeLocal) {
                localStorage.setItem('txBookmarkPagesBookmarks', JSON.stringify(bookmarks));
                localStorage.setItem('txBookmarkPagesTimestamp', Date.now());
                localStorage.setItem('txBookmarkPagesReload', '0');
            }
        },

        /**
         * @return {boolean}
         */
        get isOutdated() {
            // Check storage age
            let $expired = true;
            let timestamp = localStorage.getItem('txBookmarkPagesTimestamp');
            if (timestamp) {
                $expired = ((Date.now() - timestamp) / 1000) > settings.localStorageTTL;
            }
            // Check if a reload is requested
            let $reloadRequested = Boolean(parseInt(localStorage.getItem('txBookmarkPagesReload')));
            return $expired || $reloadRequested;
        },

        containsBookmark (bookmark) {
            return typeof this.list[bookmark.id] === 'object';
        }
    }

    const assistant = {
        updateLinks () {
            if (storage.containsBookmark(currentPage.bookmark)) {
                $('.bookmark-this-page').addClass('is-bookmarked');
            } else {
                $('.bookmark-this-page').removeClass('is-bookmarked');
            }
        },

        /**
         * Ajax callback function to update the bookmarks list and the links to bookmark a page.
         *
         * @param ajaxResult Object with properties `list` and `isBookmarked`
         */
        listQueryHandler (ajaxResult) {
            this.initList(ajaxResult.bookmarks);
            storage.list = ajaxResult.bookmarks;
            this.updateLinks();
        },
        ajax (url, data = {}) {
            data = {...data, 'tx_bookmarkpages_bookmarks[localBookmarks]': storage.list}
            $.ajax({
                url: url,
                type: 'post',
                data: data
            }).done($.proxy(this.listQueryHandler, this));
        },
        initList (bookmarks) {
            let $bookmarksList = $('#bookmarks-list'),
                $listItem = $($('#bookmark-template').html().trim());
            $bookmarksList.empty();
            Object.values(bookmarks).forEach(bookmark => {
                let $item = $listItem.clone();
                $('.bookmark-link', $item)
                    .attr('title', bookmark.title)
                    .attr('href', bookmark.url)
                    .text(bookmark.title);
                $('.bookmark-ajax-submit', $item).data('remove', bookmark.id)
                $bookmarksList.append($item);
            });
        },
        initListFromStorage () {
            let bookmarks = storage.list;
            this.initList(bookmarks);
        }
    }

    const currentPage = {
        _bookmark: null,
        init () {
            this._bookmark = $('#bookmarks').data('bookmark');
        },
        get bookmark () {
            return this._bookmark;
        }
    };

    const bookmarks = {
        $bookmarks: null,
        init () {
            this.$bookmarks = $('#bookmarks');
            if (settings.storeLocal && !storage.isOutdated) {
                assistant.initListFromStorage();
                assistant.updateLinks();
            } else {
                assistant.ajax(this.$bookmarks.data('update-ajaxuri'));
            }
        },
        add () {
            assistant.ajax(this.$bookmarks.data('add-ajaxuri'));
        },
        remove (removeID) {
            assistant.ajax(
                this.$bookmarks.data('remove-ajaxuri'),
                {'tx_bookmarkpages_bookmarks[id]': removeID ?? currentPage.bookmark.id}
            );
        }
    }

    /**
     * Bind event handlers and initialize the app when DOM is ready
     */
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
                bookmarks.remove()
            } else if (removeID) {
                bookmarks.remove(removeID)
            }
        });

        // Initialize the app
        settings.init();
        currentPage.init();
        bookmarks.init();
    });
})(jQuery);
