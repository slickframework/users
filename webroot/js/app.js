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

// Picture upload
(function ($) {
    $("#avatar").fileinput({
        overwriteInitial: true,
        theme: "fa",
        maxFileSize: 1500,
        showClose: false,
        showCaption: false,
        showBrowse: true,
        browseOnZoneClick: true,
        removeLabel: '',
        removeIcon: uploadTxt.removeIcon,
        removeTitle: uploadTxt.removeTitle,
        elErrorContainer: '#kv-avatar-errors',
        msgErrorClass: 'alert alert-danger',
        defaultPreviewContent: '<img src="' + uploadTxt.imageSrc + '" alt="' +
            uploadTxt.imageAlt + '" style="width:160px">',
        layoutTemplates: {main2: '{preview} {browse}'},
        allowedFileExtensions: ["jpg", "png", "gif"]
    });
})(jQuery);
