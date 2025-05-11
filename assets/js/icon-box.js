jQuery(window).ready(function($) {
    const objectSelect = $('.bw-iconbox-svg-animate');
    if (!objectSelect.length) {
        return;
    }
    const scroll = $(window).scrollTop();
    const bottom = $(window).height();
    const objectPosition = objectSelect.offset().top - bottom;
    if (scroll > objectPosition) {
        $('.bw-iconbox-svg-animate').addClass("run");
    } else {
        $('.bw-iconbox-svg-animate').removeClass("run");
    }
});

jQuery(window).scroll(function() {
    const objectSelect = jQuery('.bw-iconbox-svg-animate');
    if (!objectSelect.length) {
        return;
    }
    const scroll = jQuery(window).scrollTop();
    const bottom = jQuery(window).height();
    const objectPosition = objectSelect.offset().top - bottom;
    if (scroll > objectPosition) {
        jQuery('.bw-iconbox-svg-animate').addClass("run");
    } else {
        jQuery('.bw-iconbox-svg-animate').removeClass("run");
    }
});
