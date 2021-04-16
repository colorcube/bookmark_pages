(function ($) {
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
            localStorage.setItem( 'txBookmarkPagesBookmarks', JSON.stringify(bookmarks) );
        }
    }

    class BookmarksAssistant {
        /**
         * Ajax callback function to update the bookmarks list and the links to bookmark a page.
         *
         * @param ajaxResult Object with properties `list` and `isBookmarked`
         */
        static updateList( ajaxResult ) {
            $( '#bookmarks-list' ).html( ajaxResult.list );

            if ( ajaxResult.isBookmarked ) {
                $( '.bookmark-this-page' ).addClass( 'is-bookmarked' );
            } else {
                $( '.bookmark-this-page' ).removeClass('is-bookmarked' );
            }

            if ( typeof ajaxResult.localBookmarks === 'object' ) {
                BookmarkStorage.list = ajaxResult.localBookmarks;
            }
        }
        static ajax(url, data = {}) {
            data = {...data, 'tx_bookmarkpages_bookmarks[localBookmarks]': BookmarkStorage.list }
            $.ajax({
                url: url,
                type: 'post',
                data: data
            }).done( BookmarksAssistant.updateList );
        }
    }

    let bookmarks = new class {
        init() {
            this.update();
        }
        update() {
            BookmarksAssistant.ajax( $( '#bookmarks' ).data( 'update-ajaxuri' ) );
        }
        add() {
            BookmarksAssistant.ajax( $( '#bookmarks' ).data( 'add-ajaxuri' ) );
        }
        remove(removeID) {
            BookmarksAssistant.ajax(
                $( '#bookmarks' ).data( 'remove-ajaxuri' ),
                { 'tx_bookmarkpages_bookmarks[id]': removeID }
            );
        }
    }

    $(function () {
        // doing it this way:
        // $('.bookmark-ajax-submit').on('click', function (event) {
        // would not work for initially hidden elements
        $( '.bookmark-pages' ).on('click', '.bookmark-ajax-submit', function ( event ) {
            event.preventDefault();
            let $this = $(this),
                removeID = $(this).data( 'remove' );
            if ( $this.hasClass('bookmark-this-page') ){
                bookmarks.add();
            } else if ( $this.hasClass('remove-this-page') ) {
                bookmarks.remove($('#bookmarks').data('id'))
            } else if ( removeID ) {
                bookmarks.remove(removeID)
            }
        });

        // Initialise the bookmarks
        bookmarks.init();
    });
})(jQuery);
