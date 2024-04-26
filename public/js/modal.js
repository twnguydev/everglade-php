$(document).ready(function() {
    $('[data-modal-toggle]').on('click', function() {
        var modalId = $(this).data('modal-target');
        $('#' + modalId).removeClass('hidden');
    });

    $('[data-modal-hide]').on('click', function() {
        var modalId = $(this).data('modal-hide');
        $('#' + modalId).addClass('hidden');
    });
});