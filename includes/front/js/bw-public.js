// (function( jQuery ) {
jQuery(document).ready(function() {
    'use strict';

    /**
     * Modern Style 
     * Type 3
     */
    jQuery('.bw-button-box.modern.m-4 .btn-wrapper').tilt({ scale: 1.1, speed: 1000 });

    // click event
    jQuery('.bw-button-box.modern.m-4 .btn-wrapper').on('click', function(e) {
        explode(e.pageX, e.pageY);
    });

    jQuery('.bw-button-box.modern.m-4 .bw-btn-m-4').click(function(e) {
        e.preventDefault();
        setTimeout(function(url) { window.location = url }, 360, this.href);
    });

    document.addEventListener("touchstart", function() {}, true);

    // symbols
    function explode(x, y) {

        var symbolArray = [
            '#donut',
            '#circle',
            '#tri_hollow',
            '#triangle',
            '#square',
            '#squ_hollow'
        ];

        var particles = 10,
            explosion = jQuery('.bw-button-box.modern.m-4 .btn-wrapper');

        for (var i = 0; i < particles; i++) {

            var randomSymbol = Math.floor(Math.random() * symbolArray.length);
            // positioning x,y of the particles
            var x = (explosion.width() / 2) + rand(80, 150) * Math.cos(2 * Math.PI * i / rand(particles - 10, particles + 10)),
                y = (explosion.height() / 2) + rand(80, 150) * Math.sin(2 * Math.PI * i / rand(particles - 10, particles + 10)),
                deg = rand(0, 360) + 'deg',
                scale = rand(0.5, 1.1),
                // particle element creation
                elm = jQuery(
                    '<svg class="shape" style="top:' + y + 'px; left:' + x + 'px; transform: scale(' + scale + ') rotate(' + deg + ');">' +
                    '<use xlink:href="' + symbolArray[randomSymbol] + '" />' +
                    '</svg>'
                );
            if (i == 0) { // only need to target one of the symbols.
                // css3 animation end detection
                elm.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
                    elm.siblings('svg').remove().end().remove(); // remove particles when animation is over.
                });
            }
            explosion.prepend(elm);
        }
    }

    function rand(min, max) {
        return Math.floor(Math.random() * (max + 1)) + min;
    }

});

jQuery(window).scroll(function(event) {

    /* GLOBAL ANIMATE-ON-SCROLL FUNCTION */
    function animateOnScroll($elem, animFunction) {
        $elem.each(function() {
            let $elem = jQuery(this);
            jQuery(this).waypoint(function() {
                if (!$elem.hasClass('visible')) {
                    animFunction($elem);
                    $elem.addClass('visible');
                }
            }, { offset: '100%' });
        });
    }

    // USE load-pic id enywhere to load imgae as well
    /* IMAGE GROW */
    // store elements
    let $growImages = jQuery('.bw-load-img');
    // stagger text animation
    function anim_growImage($elem) {
        // animate cover
        // let cover = $elem.find('.bw-image-grow-cover')[0];
        // anime({
        //     targets: cover,
        //     translateX: [0, '-100%'],
        //     duration: 1500,
        //     easing: 'cubicBezier(.63, .01, 0, 1)'
        // });
        // animate image
        // let img = $elem.find('img')[0];
        // anime({
        //     targets: img,
        //     translateX: [-64, 0],
        //     scale: [1.5, 1],
        //     duration: 1500,
        //     easing: 'cubicBezier(.63, .01, 0, 1)'
        // });
        // animate cover
        let cover_ltr = $elem.find('.bw-image-grow-cover.ltr')[0];
        anime({
            targets: cover_ltr,
            translateX: [0, '100%'],
            duration: 1500,
            easing: 'cubicBezier(.63, .01, 0, 1)'
        });
        let cover_rtl = $elem.find('.bw-image-grow-cover.rtl')[0];
        anime({
            targets: cover_rtl,
            translateX: [0, '-100%'],
            duration: 1500,
            easing: 'cubicBezier(.63, .01, 0, 1)'
        });
        let cover_ttb = $elem.find('.bw-image-grow-cover.ttb')[0];
        anime({
            targets: cover_ttb,
            translateY: [0, '100%'],
            duration: 1500,
            easing: 'cubicBezier(.63, .01, 0, 1)'
        });
        let cover_btt = $elem.find('.bw-image-grow-cover.btt')[0];
        anime({
            targets: cover_btt,
            translateY: [0, '-100%'],
            duration: 1500,
            easing: 'cubicBezier(.63, .01, 0, 1)'
        });
        // animate image
        let img = $elem.find('img')[0];
        anime({
            targets: img,
            translateX: [-64, 0],
            scale: [1.75, 1],
            duration: 1500,
            easing: 'cubicBezier(.63, .01, 0, 1)'
        });

    }
    // run on scroll
    animateOnScroll($growImages, anim_growImage);

    /* IMAGE FADE */
    // store elements
    let $fadeImages = jQuery('.anim-scroll.image-fade');
    // stagger text animation
    function anim_fadeImage($elem) {
        // animate
        anime({
            targets: $elem[0],
            translateY: [40, 0],
            opacity: [0, 1],
            duration: 500,
            easing: 'cubicBezier(0.25, 0.1, 0.25, 1)'
        });
    }
    // run on scroll
    animateOnScroll($fadeImages, anim_fadeImage);

    /* SIMPLE FADE */
    // store elements
    let $simpleFades = jQuery('.anim-scroll.simple-fade');
    // stagger text animation
    function anim_simpleFade($elem) {
        // animate
        anime({
            targets: $elem[0],
            opacity: [0, 1],
            duration: 500,
            easing: 'cubicBezier(0.25, 0.1, 0.25, 1)'
        });
    }
    // run on scroll
    animateOnScroll($simpleFades, anim_simpleFade);

    // animate accent lines
    let $accentLines = jQuery('.bw-line');
    // or whatever the common class for these is
    // grow accent line animation
    function anim_accentLine($elem) {
        // animate
        anime({
            targets: $elem[0],
            width: [0, '100%'],
            duration: 500,
            easing: 'cubicBezier(0.25, 0.1, 0.25, 1)'
        });
    }
    // run on scroll
    animateOnScroll($accentLines, anim_accentLine);

    // animate accent to text
    let $accentTexts = jQuery('.bw-fade-text .bw-animate-text');
    // or whatever the common class for these is
    // grow accent line animation
    function anim_accentText($elem) {
        // animate
        anime({
            targets: $elem[0],
            top: 0,
            delay: 600,
            easing: 'easeOutElastic(1, 1.1)'
        });
    }
    // run on scroll
    animateOnScroll($accentTexts, anim_accentText);

    /**
     * Get background image from css to data on div
     */
    // var jqoce = ".bw-parallax-x";
    // var bg = jQuery(jqoce).css("background-image");
    // bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");
    // jQuery(jqoce).addClass("parallax-window");
    // jQuery(jqoce).attr("data-parallax", "scroll");
    // jQuery(jqoce).attr("data-image-src", bg);
    // jQuery(jqoce).attr("data-z-index", "99999");
    
    


}); // end window load wrapper