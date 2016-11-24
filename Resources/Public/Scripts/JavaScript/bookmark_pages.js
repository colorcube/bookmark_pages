(function ($) {
    $(function () {
        // doing it this way:
        // $('.bookmark-ajax-submit').on('click', function (event) {
        // would not work for initially hidden elements

        $('.bookmark-pages').on('click', '.bookmark-ajax-submit', function (event) {
            event.preventDefault();
            var $submitButton = $(this);
            var uri = $submitButton.data('ajaxuri');
            var parameters = {};
            parameters['url'] = window.location.href;
            $.ajax(
                uri,
                {
                    'type': 'post',
                    'data': parameters
                }
            ).done(function (result) {
                $('#bookmarks-list').html(result);
            });
        });
    });
})(jQuery);
