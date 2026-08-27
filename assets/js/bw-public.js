(function ($) {
  'use strict';

  // Bind the scroll handler once; a nested bind would add a new throttled
  // handler on every scroll event.
  if (window.bwPublicScrollInit) {
    return;
  }
  window.bwPublicScrollInit = true;

  const $window = $(window);
  let throttleTimeout;
  let debounceTimeout;
  let hasPendingTargets = true;

  function isElementInViewport(element) {
    const rect = element.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    return rect.top < windowHeight;
  }

  function animateOnScroll($elem, animFunction) {
    $elem.each(function () {
      const elem = this;
      if (isElementInViewport(elem) && !$(elem).hasClass('visible')) {
        animFunction($(elem));
        $(elem).addClass('visible');
      }
    });
  }

  function anim_growImage($elem) {
    function hideCover(cover) {
      if (!cover) {
        return;
      }
      cover.style.visibility = 'hidden';
    }

    const cover_ltr = $elem.find('.bw-image-grow-cover.ltr')[0];
    anime({
      targets: cover_ltr,
      translateX: [0, '101%'],
      duration: 1500,
      easing: 'cubicBezier(.63, .01, 0, 1)',
      complete: function () {
        hideCover(cover_ltr);
      }
    });

    const cover_rtl = $elem.find('.bw-image-grow-cover.rtl')[0];
    anime({
      targets: cover_rtl,
      translateX: [0, '-101%'],
      duration: 1500,
      easing: 'cubicBezier(.63, .01, 0, 1)',
      complete: function () {
        hideCover(cover_rtl);
      }
    });

    const cover_ttb = $elem.find('.bw-image-grow-cover.ttb')[0];
    anime({
      targets: cover_ttb,
      translateY: [0, '101%'],
      duration: 1500,
      easing: 'cubicBezier(.63, .01, 0, 1)',
      complete: function () {
        hideCover(cover_ttb);
      }
    });

    const cover_btt = $elem.find('.bw-image-grow-cover.btt')[0];
    anime({
      targets: cover_btt,
      translateY: [0, '-101%'],
      duration: 1500,
      easing: 'cubicBezier(.63, .01, 0, 1)',
      complete: function () {
        hideCover(cover_btt);
      }
    });

    const img = $elem.find('img')[0];
    anime({
      targets: img,
      translateX: [-64, 0],
      scale: [1.75, 1],
      duration: 1500,
      easing: 'cubicBezier(.63, .01, 0, 1)',
      complete: function () {
        if (img) {
          img.style.transform = 'none';
        }
      }
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

  function collectTargets() {
    return {
      growImages: $('.bw-load-img:not(.visible)'),
      fadeImages: $('.anim-scroll.image-fade:not(.visible)'),
      simpleFades: $('.anim-scroll.simple-fade:not(.visible)'),
      // Scope to Fade widget only - Title Animate / Typography also use .bw-line.
      accentLines: $('.elementor-widget-b_fade .bw-line:not(.visible)'),
      accentTexts: $('.bw-fade-text .bw-animate-text:not(.visible)')
    };
  }

  function onScroll() {
    if (typeof anime === 'undefined') {
      return;
    }

    const targets = collectTargets();
    const pending =
      targets.growImages.length +
      targets.fadeImages.length +
      targets.simpleFades.length +
      targets.accentLines.length +
      targets.accentTexts.length;

    if (!pending) {
      // Idle, but stay bound: Fade / Title Animate widgets can still arrive from
      // the Elementor editor, a popup, or lazy-loaded content.
      hasPendingTargets = false;
      return;
    }

    hasPendingTargets = true;
    animateOnScroll(targets.growImages, anim_growImage);
    animateOnScroll(targets.fadeImages, anim_fadeImage);
    animateOnScroll(targets.simpleFades, anim_simpleFade);
    animateOnScroll(targets.accentLines, anim_accentLine);
    animateOnScroll(targets.accentTexts, anim_accentText);
  }

  function onResize() {
    // Adjust any necessary elements on window resize
  }

  function throttledScan() {
    if (!throttleTimeout) {
      throttleTimeout = setTimeout(function () {
        throttleTimeout = null;
        onScroll();
      }, 100);
    }
  }

  /**
   * Re-arm after new markup appears. onScroll() clears hasPendingTargets once
   * everything on the page is animated, which turns the scroll handler into a
   * no-op; without this a widget added later would never animate.
   */
  function rescan() {
    hasPendingTargets = true;
    throttledScan();
  }

  $window.on('scroll.bwPublic', function () {
    if (!hasPendingTargets) {
      return;
    }
    throttledScan();
  });

  $window.on('resize.bwPublic', function () {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(onResize, 100);
  });

  // Allow other Black Widgets scripts / custom code to re-arm the scan.
  window.bwPublicRescan = rescan;

  $window.on('elementor/frontend/init', function () {
    if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
      return;
    }

    // Widgets whose markup feeds collectTargets(). Editor re-renders replace the
    // node, so the fresh copy has no .visible class and animates again.
    ['b_fade', 'b_TitleAnimate'].forEach(function (widget) {
      elementorFrontend.hooks.addAction('frontend/element_ready/' + widget + '.default', rescan);
    });
  });

  // First pass for elements already in view (no scroll required), plus a safety
  // net for markup that arrives without element_ready - loop grids, popups, or a
  // theme that loads Elementor's frontend bundle after this file.
  onScroll();
  $window.on('load', rescan);
})(jQuery);
