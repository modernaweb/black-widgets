(function ($) {
  'use strict';

  function isRtlContext() {
    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.config && typeof elementorFrontend.config.is_rtl !== 'undefined') {
      return !!elementorFrontend.config.is_rtl;
    }
    return document.documentElement.getAttribute('dir') === 'rtl' || document.body.classList.contains('rtl');
  }

  function directionalSnap(increment) {
    const snapFunc = gsap.utils.snap(increment);
    return function (raw, self) {
      const n = snapFunc(raw);
      return Math.abs(n - raw) < 1e-4 || (n < raw) === self.direction < 0
        ? n
        : self.direction < 0
          ? n - increment
          : n + increment;
    };
  }

  function destroySection(section) {
    if (!section) {
      return;
    }

    if (section._bwHorizontalTween) {
      try {
        section._bwHorizontalTween.scrollTrigger && section._bwHorizontalTween.scrollTrigger.kill();
        section._bwHorizontalTween.kill();
      } catch (e) {
        // no-op
      }
      section._bwHorizontalTween = null;
    }

    if (typeof ScrollTrigger !== 'undefined') {
      ScrollTrigger.getAll().forEach(function (st) {
        if (st.trigger === section) {
          st.kill();
        }
      });
    }

    section.scrollTriggerSet = false;
  }

  function setupSection(section) {
    if (!section) {
      return;
    }

    // Re-init safe for Elementor editor / nested refresh.
    destroySection(section);

    const elements = gsap.utils.toArray('.elementor', section);

    // Need at least 2 panels - otherwise xPercent/snap become no-op or Infinity.
    if (elements.length < 2) {
      return;
    }

    // RTL sites need the opposite travel direction so panels advance in reading order.
    const rtl = isRtlContext();
    const travel = (rtl ? 100 : -100) * (elements.length - 1);

    const tween = gsap.to(elements, {
      xPercent: travel,
      ease: 'none',
      scrollTrigger: {
        trigger: section,
        pin: true,
        scrub: 0.35,
        snap: {
          snapTo: directionalSnap(1 / (elements.length - 1)),
          duration: { min: 0.08, max: 0.28 },
          delay: 0,
          ease: 'power1.inOut'
        },
        // Match travel distance to panel count-1 (extra *length left dead scroll after last panel).
        end: () => '+=' + Math.max(section.offsetWidth * (elements.length - 1), 1),
        invalidateOnRefresh: true
      }
    });

    section._bwHorizontalTween = tween;
    section.scrollTriggerSet = true;
  }

  function initHorizontal($scope) {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      return;
    }

    gsap.registerPlugin(ScrollTrigger);

    const root = $scope && $scope[0] ? $scope[0] : null;
    if (!root) {
      return;
    }

    const sections = root.querySelectorAll('.bw-section');
    sections.forEach(setupSection);

    if (root.classList && root.classList.contains('bw-section')) {
      setupSection(root);
    }
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(
      'frontend/element_ready/b_gsap_horizontal_scrolling.default',
      function ($scope) {
        initHorizontal($scope);
      }
    );
  });
})(jQuery);
