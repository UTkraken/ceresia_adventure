$(document).ready(function () {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
    }).on('click', 'table button', function () {
        $btn = $(this);
        var func = $btn.data('action');
        window[func]($btn);
    });
});