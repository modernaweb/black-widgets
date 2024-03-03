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

// jQuery(function($) {
jQuery(window).scroll(function(event) {
    const $window = jQuery(window);
    const $growImages = jQuery('.bw-load-img');
    const $fadeImages = jQuery('.anim-scroll.image-fade');
    const $simpleFades = jQuery('.anim-scroll.simple-fade');
    const $accentLines = jQuery('.bw-line');
    const $accentTexts = jQuery('.bw-fade-text .bw-animate-text');
  
    let throttleTimeout;
    let debounceTimeout;
  
    // Throttle scroll event
    $window.on('scroll', function() {
      if (!throttleTimeout) {
        throttleTimeout = setTimeout(function() {
          throttleTimeout = null;
          onScroll();
        }, 100);
      }
    });
  
    // Debounce resize event
    $window.on('resize', function() {
      clearTimeout(debounceTimeout);
      debounceTimeout = setTimeout(onResize, 100);
    });
  
    function onScroll() {
      animateOnScroll($growImages, anim_growImage);
      animateOnScroll($fadeImages, anim_fadeImage);
      animateOnScroll($simpleFades, anim_simpleFade);
      animateOnScroll($accentLines, anim_accentLine);
      animateOnScroll($accentTexts, anim_accentText);
    }
  
    function onResize() {
      // Adjust any necessary elements on window resize
    }
  
    function animateOnScroll($elem, animFunction) {
      $elem.each(function() {
        const elem = this;
        if (isElementInViewport(elem) && !jQuery(elem).hasClass('visible')) {
          animFunction(jQuery(elem));
          jQuery(elem).addClass('visible');
        }
      });
    }
  
    function isElementInViewport(element) {
      const rect = element.getBoundingClientRect();
      const windowHeight = window.innerHeight || document.documentElement.clientHeight;
      return rect.top < windowHeight;
    }
  
    function anim_growImage($elem) {
      const cover_ltr = $elem.find('.bw-image-grow-cover.ltr')[0];
      anime({
        targets: cover_ltr,
        translateX: [0, '100%'],
        duration: 1500,
        easing: 'cubicBezier(.63, .01, 0, 1)'
      });
  
      const cover_rtl = $elem.find('.bw-image-grow-cover.rtl')[0];
      anime({
        targets: cover_rtl,
        translateX: [0, '-100%'],
        duration: 1500,
        easing: 'cubicBezier(.63, .01, 0, 1)'
      });
  
      const cover_ttb = $elem.find('.bw-image-grow-cover.ttb')[0];
      anime({
        targets: cover_ttb,
        translateY: [0, '100%'],
        duration: 1500,
        easing: 'cubicBezier(.63, .01, 0, 1)'
      });
  
      const cover_btt = $elem.find('.bw-image-grow-cover.btt')[0];
      anime({
        targets: cover_btt,
        translateY: [0, '-100%'],
        duration: 1500,
        easing: 'cubicBezier(.63, .01, 0, 1)'
      });
  
      const img = $elem.find('img')[0];
      anime({
        targets: img,
        translateX: [-64, 0],
        scale: [1.75, 1],
        duration: 1500,
        easing: 'cubicBezier(.63, .01, 0, 1)'
      });
    }
  
    function anim_fadeImage($elem) {
      anime({
        targets: $elem[0],
        translateY: [40, 0],
        opacity: [0, 1],
        duration: 500,
        easing: 'cubicBezier(0.25, 0.1, 0.25, 1)'
      });
    }
  
    function anim_simpleFade($elem) {
      anime({
        targets: $elem[0],
        opacity: [0, 1],
        duration: 500,
        easing: 'cubicBezier(0.25, 0.1, 0.25, 1)'
      });
    }
  
    function anim_accentLine($elem) {
      anime({
        targets: $elem[0],
        width: [0, '100%'],
        duration: 500,
        easing: 'cubicBezier(0.25, 0.1, 0.25, 1)'
      });
    }
  
    function anim_accentText($elem) {
      anime({
        targets: $elem[0],
        top: 0,
        delay: 600,
        easing: 'easeOutElastic(1, 1.1)'
      });
    }
  });
  
  