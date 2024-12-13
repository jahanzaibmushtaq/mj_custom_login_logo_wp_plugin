jQuery(document).ready(function ($) {
    $('#upload_logo_button').click(function (e) {
        e.preventDefault();
        var mediaUploader = wp.media({
            title: 'Select Custom Logo',
            button: {
                text: 'Use this logo'
            },
            multiple: false
        }).on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#cll_logo_url').val(attachment.url);
        }).open();
    });
});
