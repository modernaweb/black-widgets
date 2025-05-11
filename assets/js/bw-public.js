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
