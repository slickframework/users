(function ($) {
    $('[data-toggle="sidebar-toggle"]').on('click', function (e) {
        e.preventDefault();
        $('.js-sidebar-wrapper').toggleClass('sidebar-wrapper--visible');
    });
    $('.js-sidebar-wrapper').on('click', function () {
        $('.js-sidebar-wrapper').toggleClass('sidebar-wrapper--visible');
    });
    $('.js-sidebar-wrapper .sidebar').on('click', function (e) {
        e.stopPropagation();
    })
})(jQuery);
