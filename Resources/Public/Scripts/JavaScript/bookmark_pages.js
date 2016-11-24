(function ($) {
    $(function () {
        // doing it this way:
        // $('.bookmark-ajax-submit').on('click', function (event) {
        // would not work for initially hidden elements

        $('.bookmark-pages').on('click', '.bookmark-ajax-submit', function (event) {
            event.preventDefault();
            var $submitButton = $(this);
            var uri = $submitButton.data('ajaxuri');
            $.ajax(
                uri,
                {
                    'type': 'get'
                }
            ).done(function (result) {
                $('#bookmarks-list').html(result);
            });
        });
    });
})(jQuery);
