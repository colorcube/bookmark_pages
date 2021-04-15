(function ($) {
    class BookmarkManager {

    }

    /**
     * Ajax callback function to update the bookmarks list and the links to bookmark a page.
     *
     * @param result Ajax result object with properties list and isBookmarked
     */
    function updateList ( result ) {
        $( '#bookmarks-list' ).html( result.list );

        if ( result.isBookmarked ) {
            $( '.bookmark-this-page' ).addClass( 'is-bookmarked' );
        } else {
            $( '.bookmark-this-page' ).removeClass('is-bookmarked' );
        }

        if ( typeof result.localBookmarks === 'object' ) {
            localStorage.setItem( 'txBookmarkPagesLocalBookmarks', JSON.stringify(result.localBookmarks) );
        }

        console.log( result );
    }


    $(function () {

        // doing it this way:
        // $('.bookmark-ajax-submit').on('click', function (event) {
        // would not work for initially hidden elements

        $( '.bookmark-pages' ).on('click', '.bookmark-ajax-submit', function ( event ) {
            event.preventDefault();
            var $submitButton = $( this );
            var uri = $submitButton.data( 'ajaxuri' );
            $.ajax(
                uri,
                {
                    'type': 'post'
                }
            ).done( updateList );
        });

        // Load the list
        $.ajax({
            url: $( '#bookmarks' ).data( 'list-ajaxuri' ),
            type: 'post',
            data: { 'tx_bookmarkpages_bookmarks[localBookmarks]': JSON.parse(localStorage.getItem('txBookmarkPagesLocalBookmarks')) }
        }).done( updateList );

    });
})(jQuery);
