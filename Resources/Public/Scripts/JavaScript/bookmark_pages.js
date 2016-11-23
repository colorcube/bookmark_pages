(function ($) {
    $(function () {
        $('.ajax-submit').on('click', function (event) {
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
