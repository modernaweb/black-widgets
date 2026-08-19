(function ($) {
  'use strict';

  const API_KEY = 'bwIconBoxApi';
  const watched = new Set();
  let ticking = false;
  let scrollBound = false;

  function isInViewport(el) {
    const rect = el.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    // Legacy rule: run when element top is above the viewport bottom
    // (stays on after scrolling past; clears only when still below the fold).
    return rect.top < windowHeight;
  }

  function syncAll() {
    watched.forEach(function (el) {
      if (!document.documentElement.contains(el)) {
        watched.delete(el);
        return;
      }
      $(el).toggleClass('run', isInViewport(el));
    });
  }

  function onScroll() {
    if (ticking) {
      return;
    }
    ticking = true;
    window.requestAnimationFrame(function () {
      ticking = false;
      syncAll();
    });
  }

  function ensureScrollBinding() {
    if (scrollBound) {
      return;
    }
    scrollBound = true;
    $(window).on('scroll.bwIconBoxShared resize.bwIconBoxShared', onScroll);
  }

  function releaseScrollBinding() {
    if (watched.size > 0) {
      return;
    }
    if (!scrollBound) {
      return;
    }
    scrollBound = false;
    $(window).off('scroll.bwIconBoxShared resize.bwIconBoxShared');
  }

  function destroyIconBox($el) {
    const api = $el.data(API_KEY);
    if (!api || !api.el) {
      return;
    }
    watched.delete(api.el);
    $el.removeData(API_KEY);
    releaseScrollBinding();
  }

  function initIconBoxSvg($scope) {
    let $targets = $scope.find('.bw-iconbox-svg-animate');
    if ($scope.hasClass('bw-iconbox-svg-animate')) {
      $targets = $targets.add($scope);
    }
    if (!$targets.length) {
      return;
    }

    $targets.each(function () {
      const el = this;
      const $el = $(el);
      destroyIconBox($el);
      watched.add(el);
      $el.data(API_KEY, { el: el });
    });

    ensureScrollBinding();
    syncAll();
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/b_icon_box.default', function ($scope) {
      initIconBoxSvg($scope);
    });
  });
})(jQuery);
