(function ($) {
    $(function () {
        $('#ajax-submit').on('click', function (event) {
            event.preventDefault();
            var $nameField = $('#greeted-name');
            if (!$nameField.val()) {
                alert('Please enter a name!');
                return;
            }
            var $submitButton = $(this);
            var uri = $submitButton.data('ajaxuri');
            var parameters = {};
            parameters[$nameField.attr('name')] = $nameField.val();
            $.ajax(
                uri,
                {
                    'type': 'post',
                    'data': parameters
                }
            ).done(function (result) {
                $('#hello').html(result);
            });
        });
    });
})(jQuery);
