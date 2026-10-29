(function ($) {
  'use strict';

  const API_KEY = 'bwIconBoxApi';
  const DRAW_SEL = 'path, line, polyline, circle, rect, ellipse, polygon';
  const watched = new Set();
  let ticking = false;
  let scrollBound = false;
  let editModeSticky = false;
  let hooksBound = false;

  function prefersReducedMotion() {
    return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  function hasGsap() {
    return typeof gsap !== 'undefined';
  }

  function isElementorEditMode() {
    if (editModeSticky) {
      return true;
    }
    try {
      if (
        window.bwIconBoxEnv &&
        (bwIconBoxEnv.isEditor === true || bwIconBoxEnv.isEditor === 1 || bwIconBoxEnv.isEditor === '1')
      ) {
        editModeSticky = true;
        return true;
      }
      if (document.querySelector('[data-bw-elementor-editor]')) {
        editModeSticky = true;
        return true;
      }
      if (document.querySelector('.elementor-element-edit-mode, .elementor-editor-active .elementor-element')) {
        editModeSticky = true;
        return true;
      }
      if (window.elementorFrontend) {
        if (typeof elementorFrontend.isEditMode === 'function' && elementorFrontend.isEditMode()) {
          editModeSticky = true;
          return true;
        }
        if (
          elementorFrontend.config &&
          elementorFrontend.config.environmentMode &&
          elementorFrontend.config.environmentMode.edit
        ) {
          editModeSticky = true;
          return true;
        }
      }
    } catch (e) {
      return editModeSticky;
    }
    return false;
  }

  function getMediaEl(box) {
    return box.querySelector('.bw-iconbox-icon, .bw-iconbox-img');
  }

  function paintValue(node, cssProp, attrName) {
    const attr = (node.getAttribute(attrName) || '').trim();
    if (attr && attr !== 'none' && attr !== 'transparent') {
      return attr;
    }
    try {
      const computed = window.getComputedStyle(node)[cssProp];
      if (
        computed &&
        computed !== 'none' &&
        computed !== 'transparent' &&
        computed !== 'rgba(0, 0, 0, 0)'
      ) {
        return computed;
      }
    } catch (e) {
      return '';
    }
    return '';
  }

  function clearDrawStyles(root) {
    if (!root) {
      return;
    }
    root.querySelectorAll(DRAW_SEL).forEach(function (node) {
      node.style.strokeDasharray = '';
      node.style.strokeDashoffset = '';
      node.style.fillOpacity = '';
      node.style.transition = '';
      if (node.getAttribute('data-bw-drew-stroke') === '1') {
        node.style.stroke = '';
        node.style.strokeWidth = '';
        node.removeAttribute('data-bw-drew-stroke');
      }
    });
  }

  function prepareDrawNodes(root) {
    if (!root) {
      return [];
    }
    const prepared = [];
    root.querySelectorAll(DRAW_SEL).forEach(function (node) {
      if (typeof node.getTotalLength !== 'function') {
        return;
      }
      let length = 0;
      try {
        length = node.getTotalLength();
      } catch (e) {
        return;
      }
      if (!Number.isFinite(length) || length <= 0) {
        return;
      }

      const stroke = paintValue(node, 'stroke', 'stroke');
      const fill = paintValue(node, 'fill', 'fill');
      if (!stroke) {
        node.style.stroke = fill && fill !== 'none' ? fill : 'currentColor';
        if (!node.getAttribute('stroke-width') && !node.style.strokeWidth) {
          node.style.strokeWidth = '2';
        }
        node.setAttribute('data-bw-drew-stroke', '1');
      }

      node.style.transition = 'none';
      node.style.strokeDasharray = String(length);
      node.style.strokeDashoffset = String(length);
      node.style.fillOpacity = '0';
      prepared.push({ node: node, length: length });
    });
    return prepared;
  }

  function isInViewport(el) {
    if (!el || !el.getBoundingClientRect) {
      return false;
    }
    const rect = el.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    return rect.top < windowHeight * 0.92 && rect.bottom > 0;
  }

  function boxSignature(el) {
    return [
      el.getAttribute('data-bw-anim') || '',
      el.getAttribute('data-bw-anim-trigger') || '',
      el.getAttribute('data-bw-anim-hover') || '',
      el.getAttribute('data-bw-anim-loop') || '',
      el.getAttribute('data-bw-anim-duration') || '',
      el.getAttribute('data-bw-anim-delay') || '',
      el.getAttribute('data-bw-anim-ease') || '',
      el.getAttribute('data-bw-media') || '',
      el.classList.contains('bw-iconbox-svg-animate') ? '1' : '0'
    ].join('|');
  }

  function syncLegacy() {
    watched.forEach(function (el) {
      if (!document.documentElement.contains(el)) {
        watched.delete(el);
        return;
      }
      if (el.getAttribute('data-bw-anim')) {
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
      syncLegacy();
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

  function killTween(tw) {
    if (!tw) {
      return;
    }
    if (typeof tw.kill === 'function') {
      tw.kill();
    }
  }

  function refreshScrollTrigger() {
    if (typeof ScrollTrigger !== 'undefined' && typeof ScrollTrigger.refresh === 'function') {
      ScrollTrigger.refresh();
    }
  }

  function destroyIconBox($el) {
    const api = $el.data(API_KEY);
    if (!api) {
      return;
    }
    if (api.el) {
      watched.delete(api.el);
    }
    if (api.hoverOff) {
      api.hoverOff();
    }
    if (api.fallbackTimer) {
      clearTimeout(api.fallbackTimer);
    }
    killTween(api.entrance);
    killTween(api.loop);
    killTween(api.hover);
    if (api.st && typeof api.st.kill === 'function') {
      api.st.kill();
    }
    if (api.el) {
      api.el.classList.remove('bw-drawing', 'bw-gsap-hover-scale');
    }
    if (api.media) {
      clearDrawStyles(api.media);
      if (hasGsap()) {
        gsap.set(api.media, { clearProps: 'opacity,x,y,scale,rotation,transform' });
        const inner = api.media.querySelector('svg, i, img');
        if (inner) {
          gsap.set(inner, { clearProps: 'opacity,x,y,scale,rotation,transform' });
        }
      }
    }
    $el.removeData(API_KEY);
    releaseScrollBinding();
  }

  function playEntrance(media, type, duration, delay, ease) {
    if (!media || !hasGsap()) {
      return null;
    }
    showMediaWrap(media);
    const d = Math.max(0.1, (duration || 800) / 1000);
    const del = Math.max(0, (delay || 0) / 1000);
    const e = ease || 'power2.out';

    if (type === 'draw') {
      const prepared = prepareDrawNodes(media);
      if (!prepared.length) {
        return gsap.fromTo(media, { opacity: 0 }, { opacity: 1, duration: d, delay: del, ease: e });
      }
      const box = media.closest('.bw-iconbox');
      if (box) {
        box.classList.add('bw-drawing');
      }
      const tl = gsap.timeline({
        delay: del,
        onComplete: function () {
          if (box) {
            box.classList.remove('bw-drawing');
          }
        }
      });
      prepared.forEach(function (item) {
        tl.fromTo(
          item.node,
          { strokeDashoffset: item.length, fillOpacity: 0 },
          { strokeDashoffset: 0, duration: d, ease: e, overwrite: 'auto' },
          0
        );
        tl.to(
          item.node,
          { fillOpacity: 1, duration: Math.max(0.18, d * 0.35), ease: 'power1.out', overwrite: 'auto' },
          d * 0.6
        );
      });
      return tl;
    }

    const motion = getMotionTarget(media);
    const from = { opacity: 0, x: 0, y: 0, scale: 1, rotation: 0 };
    const to = { opacity: 1, x: 0, y: 0, scale: 1, rotation: 0, duration: d, delay: del, ease: e, overwrite: 'auto' };
    if (type === 'fade_up') {
      from.y = 24;
    } else if (type === 'scale') {
      from.scale = 0.6;
    } else if (type === 'rotate') {
      from.rotation = -18;
    } else if (type === 'bounce') {
      from.y = -28;
      to.ease = 'bounce.out';
    }
    return gsap.fromTo(motion, from, to);
  }

  function resetEntrance(media, type) {
    if (!media || !hasGsap()) {
      return;
    }
    showMediaWrap(media);
    const motion = getMotionTarget(media);
    gsap.set(motion, { clearProps: 'x,y,scale,rotation,transform' });
    if (type === 'draw') {
      prepareDrawNodes(media);
    } else {
      clearDrawStyles(media);
      gsap.set(motion, { opacity: 0 });
    }
  }

  function startLoop(media, loop, duration) {
    if (!media || !loop || loop === 'none' || !hasGsap()) {
      return null;
    }
    const target = getMotionTarget(media);
    const d = Math.max(0.4, (duration || 800) / 1000);
    if (loop === 'float') {
      return gsap.to(target, { y: -8, duration: 1.4, yoyo: true, repeat: -1, ease: 'sine.inOut', overwrite: 'auto' });
    }
    if (loop === 'pulse') {
      return gsap.to(target, { scale: 1.08, duration: 0.9, yoyo: true, repeat: -1, ease: 'sine.inOut', overwrite: 'auto' });
    }
    if (loop === 'spin') {
      return gsap.to(target, { rotation: 360, duration: Math.max(1.2, d), repeat: -1, ease: 'none', overwrite: 'auto' });
    }
    return null;
  }

  function getMotionTarget(media) {
    return (media && media.querySelector('svg, i, img')) || media;
  }

  function showMediaWrap(media) {
    if (!media || !hasGsap()) {
      return;
    }
    gsap.set(media, { clearProps: 'opacity' });
  }

  function bindHover(box, media, hover, duration, type, api) {
    if (!box || !media || !hover || hover === 'none' || !hasGsap()) {
      return null;
    }
    if (hover === 'scale' && box.classList.contains('bw-position-2')) {
      return null;
    }
    const target = getMotionTarget(media);
    const d = Math.min(0.45, Math.max(0.18, (duration || 800) / 2200));
    let current = null;

    function pauseLoop() {
      if (api && api.loop && typeof api.loop.pause === 'function') {
        api.loop.pause();
      }
    }

    function resumeLoop() {
      if (api && api.loop && typeof api.loop.resume === 'function') {
        api.loop.resume();
      }
    }

    function enter() {
      pauseLoop();
      killTween(current);
      if (hover === 'scale') {
        current = gsap.to(target, { scale: 1.12, duration: d, ease: 'power2.out', overwrite: 'auto', force3D: true });
      } else if (hover === 'rotate') {
        current = gsap.to(target, { rotation: 12, duration: d, ease: 'power2.out', overwrite: 'auto', force3D: true });
      } else if (hover === 'draw') {
        current = playEntrance(media, 'draw', duration, 0, 'power2.out');
      }
    }

    function leave() {
      killTween(current);
      if (hover === 'draw') {
        if (type === 'draw') {
          prepareDrawNodes(media);
        } else {
          clearDrawStyles(media);
        }
        resumeLoop();
        return;
      }
      current = gsap.to(target, {
        scale: 1,
        rotation: 0,
        duration: d,
        ease: 'power2.out',
        overwrite: 'auto',
        force3D: true,
        onComplete: resumeLoop
      });
    }

    box.addEventListener('mouseenter', enter);
    box.addEventListener('mouseleave', leave);

    return function () {
      box.removeEventListener('mouseenter', enter);
      box.removeEventListener('mouseleave', leave);
      killTween(current);
    };
  }

  function initGsapBox(box) {
    if (prefersReducedMotion()) {
      const media = getMediaEl(box);
      if (media && hasGsap()) {
        showMediaWrap(media);
        gsap.set(getMotionTarget(media), { opacity: 1, clearProps: 'x,y,scale,rotation,transform' });
      }
      return;
    }

    if (!hasGsap()) {
      return;
    }

    const media = getMediaEl(box);
    if (!media) {
      return;
    }

    const mediaKind = box.getAttribute('data-bw-media') || '';
    let type = box.getAttribute('data-bw-anim') || 'fade_up';
    let hover = box.getAttribute('data-bw-anim-hover') || 'none';
    if (type === 'draw' && mediaKind !== 'svg') {
      type = 'fade_up';
    }
    if (hover === 'draw' && mediaKind !== 'svg') {
      hover = 'none';
    }
    const trigger = box.getAttribute('data-bw-anim-trigger') || 'scroll_once';
    const loop = box.getAttribute('data-bw-anim-loop') || 'none';
    const duration = parseInt(box.getAttribute('data-bw-anim-duration'), 10) || 800;
    const delay = parseInt(box.getAttribute('data-bw-anim-delay'), 10) || 0;
    const ease = box.getAttribute('data-bw-anim-ease') || 'power2.out';
    const inEditor = isElementorEditMode();

    const $el = $(box);
    const api = {
      el: box,
      media: media,
      sig: boxSignature(box),
      played: false,
      entrance: null,
      loop: null,
      hover: null,
      hoverOff: null,
      st: null,
      fallbackTimer: null
    };

    if (typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);
    }

    function play() {
      api.played = true;
      showMediaWrap(media);
      killTween(api.entrance);
      killTween(api.loop);
      api.entrance = playEntrance(media, type, duration, delay, ease);
      if (loop !== 'none' && api.entrance && typeof api.entrance.eventCallback === 'function') {
        api.entrance.eventCallback('onComplete', function () {
          api.loop = startLoop(media, loop, duration);
        });
      } else if (loop !== 'none') {
        api.loop = startLoop(media, loop, duration);
      }
    }

    function reset() {
      api.played = false;
      killTween(api.entrance);
      killTween(api.loop);
      api.entrance = null;
      api.loop = null;
      resetEntrance(media, type);
    }

    function revealIfStuck() {
      if (!media || !document.documentElement.contains(media)) {
        return;
      }
      try {
        if (parseFloat(window.getComputedStyle(media).opacity) < 0.95) {
          showMediaWrap(media);
        }
      } catch (e) {
        showMediaWrap(media);
      }
      if (!api.played) {
        play();
      }
    }

    if (inEditor || trigger === 'on_load') {
      play();
    } else {
      resetEntrance(media, type === 'draw' ? 'draw' : type);

      if (isInViewport(box)) {
        play();
      } else if (typeof ScrollTrigger !== 'undefined') {
        api.st = ScrollTrigger.create({
          trigger: box,
          start: 'top 85%',
          invalidateOnRefresh: true,
          once: trigger === 'scroll_once',
          onEnter: play,
          onLeave: trigger === 'scroll_every' ? reset : undefined,
          onEnterBack: trigger === 'scroll_every' ? play : undefined,
          onLeaveBack: trigger === 'scroll_every' ? reset : undefined
        });
        refreshScrollTrigger();
      } else {
        play();
      }

      api.fallbackTimer = setTimeout(revealIfStuck, 400);
    }

    api.hoverOff = bindHover(box, media, hover, duration, type, api);
    $el.data(API_KEY, api);
  }

  function initLegacyBox(box) {
    const $el = $(box);
    prepareDrawNodes(box);
    watched.add(box);
    $el.data(API_KEY, { el: box, sig: boxSignature(box) });
    ensureScrollBinding();
  }

  function whenGsapReady(cb, attempt) {
    if (hasGsap() || attempt > 24) {
      cb();
      return;
    }
    window.setTimeout(function () {
      whenGsapReady(cb, (attempt || 0) + 1);
    }, 50);
  }

  function initIconBox($scope) {
    let $targets = $scope.find('.bw-iconbox');
    if ($scope.hasClass('bw-iconbox')) {
      $targets = $targets.add($scope);
    }
    if (!$targets.length) {
      return;
    }

    $targets.each(function () {
      const el = this;
      const $el = $(el);
      const sig = boxSignature(el);
      const existing = $el.data(API_KEY);
      if (existing && existing.sig === sig && existing.el && document.documentElement.contains(existing.el)) {
        return;
      }

      destroyIconBox($el);

      if (el.getAttribute('data-bw-anim')) {
        $el.data(API_KEY, { el: el, sig: sig, pending: true });
        whenGsapReady(function () {
          const latest = $el.data(API_KEY);
          if (latest && latest.sig === sig && !latest.pending) {
            return;
          }
          initGsapBox(el);
        });
        return;
      }

      if ($el.hasClass('bw-iconbox-svg-animate')) {
        initLegacyBox(el);
      }
    });

    syncLegacy();
  }

  window.bwInitIconBox = function ($scope) {
    if (!$scope || !$scope.jquery) {
      $scope = jQuery($scope);
    }
    initIconBox($scope);
  };

  function onElementReady($scope) {
    initIconBox($scope);
  }

  function bindHooks() {
    if (hooksBound) {
      return true;
    }
    if (!window.elementorFrontend || !elementorFrontend.hooks || typeof elementorFrontend.hooks.addAction !== 'function') {
      return false;
    }
    hooksBound = true;
    elementorFrontend.hooks.addAction('frontend/element_ready/b_icon_box.default', onElementReady);
    return true;
  }

  function bootExisting() {
    $('.elementor-widget-b_icon_box, .bw-iconbox[data-bw-anim], .bw-iconbox-svg-animate').each(function () {
      const $node = $(this).hasClass('bw-iconbox') ? $(this) : $(this);
      initIconBox($node);
    });
  }

  if (!bindHooks()) {
    $(window).on('elementor/frontend/init', function () {
      bindHooks();
    });
  } else {
    bootExisting();
  }

  $(function () {
    bindHooks();
    bootExisting();
    window.setTimeout(function () {
      refreshScrollTrigger();
      bootExisting();
    }, 200);
    window.setTimeout(refreshScrollTrigger, 800);
  });

  $(window).on('load.bwIconBox', function () {
    refreshScrollTrigger();
    bootExisting();
  });
})(jQuery);
