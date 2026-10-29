(function ($) {
  'use strict';

  function isRtlContext() {
    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.config && typeof elementorFrontend.config.is_rtl !== 'undefined') {
      return !!elementorFrontend.config.is_rtl;
    }
    return document.documentElement.getAttribute('dir') === 'rtl' || document.body.classList.contains('rtl');
  }

  function isEditMode() {
    try {
      if (typeof elementorFrontend !== 'undefined') {
        if (elementorFrontend.isEditMode && elementorFrontend.isEditMode()) {
          return true;
        }
        if (elementorFrontend.config && elementorFrontend.config.environmentMode && elementorFrontend.config.environmentMode.edit) {
          return true;
        }
      }
      if (/[?&]elementor-preview=/.test(String(window.location.search || ''))) {
        return true;
      }
      if (document.body && (
        document.body.classList.contains('elementor-editor-active') ||
        document.body.classList.contains('elementor-editor-preview')
      )) {
        return true;
      }
    } catch (e) {
      // no-op
    }
    return false;
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

  /**
   * Smooth-scroll engines (Lenis) run their own rAF loop and only emit a native
   * scroll event afterwards, so ScrollTrigger updates a frame behind the real
   * position. Without this bridge the pinned track visibly drags behind.
   */
  function findLenis() {
    const holders = [window.MWS_SmoothScroll, window.lenis, window.Lenis && window.Lenis.instance];
    for (let i = 0; i < holders.length; i++) {
      const holder = holders[i];
      if (!holder) {
        continue;
      }
      const candidate = holder.instance || holder.lenis || holder.engine || holder;
      if (candidate && typeof candidate.on === 'function' && typeof candidate.scroll === 'number') {
        return candidate;
      }
    }
    return null;
  }

  let smoothBridged = false;
  function bridgeSmoothScroll() {
    if (smoothBridged || typeof ScrollTrigger === 'undefined') {
      return;
    }
    const lenis = findLenis();
    if (!lenis) {
      return;
    }
    smoothBridged = true;
    lenis.on('scroll', ScrollTrigger.update);
    ScrollTrigger.refresh();
  }

  function hasSmoothScroll() {
    return !!(findLenis() || document.documentElement.classList.contains('lenis'));
  }

  let pluginReady = false;

  function ensurePlugin() {
    if (pluginReady) {
      return true;
    }
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      return false;
    }
    gsap.registerPlugin(ScrollTrigger);
    pluginReady = true;
    return true;
  }

  function getWrapper(section) {
    return (section && section.closest && section.closest('.bw-horizontal-section')) || section;
  }

  function getTrack(section) {
    if (!section || !section.children) {
      return section;
    }
    for (let i = 0; i < section.children.length; i++) {
      if (section.children[i].classList && section.children[i].classList.contains('bw-hscroll-track')) {
        return section.children[i];
      }
    }
    return section;
  }

  function getPanels(section) {
    const list = [];
    const parent = getTrack(section);
    if (!parent || !parent.children) {
      return list;
    }
    for (let i = 0; i < parent.children.length; i++) {
      const child = parent.children[i];
      if (child.classList && child.classList.contains('elementor')) {
        list.push(child);
      }
    }
    return list;
  }

  function syncPanelWidth(section) {
    const w = section ? (section.clientWidth || section.offsetWidth || 0) : 0;
    if (section && w) {
      section.style.setProperty('--bw-hscroll-panel-w', w + 'px');
    }
    return w;
  }

  function trackX(section, rtl, index) {
    return (rtl ? 1 : -1) * syncPanelWidth(section) * index;
  }

  function readStackBelow(section) {
    const root = getWrapper(section);
    const value = root && root.getAttribute('data-stack-below');
    return value || 'mobile';
  }

  function breakpointValue(name, fallback) {
    try {
      const responsive = elementorFrontend && elementorFrontend.config && elementorFrontend.config.responsive;
      const groups = responsive && (responsive.activeBreakpoints || responsive.breakpoints);
      if (groups && groups[name]) {
        const raw = groups[name];
        const parsed = parseInt(typeof raw === 'object' ? raw.value : raw, 10);
        if (parsed) {
          return parsed;
        }
      }
    } catch (e) {
      // no-op
    }
    return fallback;
  }

  function shouldStack(section) {
    const stackBelow = readStackBelow(section);
    if (stackBelow === 'never' || isEditMode()) {
      return false;
    }
    const max = stackBelow === 'tablet' ? breakpointValue('tablet', 1024) : breakpointValue('mobile', 767);
    return window.innerWidth <= max;
  }

  function parseSettings(el) {
    const raw = el.getAttribute('data-settings');
    if (!raw) {
      return {};
    }
    try {
      return JSON.parse(raw);
    } catch (e) {
      return {};
    }
  }

  function getEntranceName(el) {
    const settings = parseSettings(el);
    const name = settings._animation || settings.animation || '';
    return name && name !== 'none' ? name : '';
  }

  function getEntranceDelay(el) {
    const settings = parseSettings(el);
    return parseInt(settings._animation_delay || settings.animation_delay || 0, 10) || 0;
  }

  function findAnimatedEls(panel) {
    const seen = [];
    const nodes = panel.querySelectorAll('[data-settings], .elementor-invisible');
    for (let i = 0; i < nodes.length; i++) {
      const el = nodes[i];
      if (seen.indexOf(el) !== -1) {
        continue;
      }
      if (getEntranceName(el) || el.classList.contains('elementor-invisible')) {
        seen.push(el);
      }
    }
    return seen;
  }

  function holdPanelAnims(panel) {
    findAnimatedEls(panel).forEach(function (el) {
      const name = getEntranceName(el);
      if (name) {
        el.classList.remove('animated', name);
      }
      el.classList.add('elementor-invisible');
    });
  }

  function playPanelAnims(panel) {
    findAnimatedEls(panel).forEach(function (el) {
      const name = getEntranceName(el);
      const delay = getEntranceDelay(el);
      if (name) {
        el.classList.remove('animated', name);
      }
      el.classList.add('elementor-invisible');
      void el.offsetWidth;
      window.setTimeout(function () {
        el.classList.remove('elementor-invisible');
        if (name) {
          el.classList.add('animated', name);
        }
      }, delay);
    });
  }

  function nestedTriggers(panel, section) {
    if (typeof ScrollTrigger === 'undefined') {
      return [];
    }
    return ScrollTrigger.getAll().filter(function (st) {
      return st.trigger && st.trigger !== section && panel.contains(st.trigger);
    });
  }

  function pauseNested(panel, section) {
    nestedTriggers(panel, section).forEach(function (st) {
      if (st.vars && st.vars.containerAnimation) {
        return;
      }
      try {
        if (st.animation) {
          st.animation.pause(0);
        }
        st.disable(false);
      } catch (e) {
        // no-op
      }
    });
  }

  function playNested(panel, section) {
    nestedTriggers(panel, section).forEach(function (st) {
      if (st.vars && st.vars.containerAnimation) {
        return;
      }
      try {
        st.enable();
        if (st.animation) {
          st.animation.restart(true);
        }
      } catch (e) {
        // no-op
      }
    });
  }

  function kickNestedWidgets(section) {
    if (typeof window.bwInitTypographyAnimate === 'function') {
      window.bwInitTypographyAnimate(section);
    }
    if (typeof window.bwInitScrollHeat === 'function') {
      window.bwInitScrollHeat(section);
    }
  }

  function setActivePanel(section, panels, index, force) {
    if (!force && section._bwActiveIndex === index) {
      return;
    }

    const prev = section._bwActiveIndex;
    if (typeof prev === 'number' && prev !== index && panels[prev]) {
      panels[prev].classList.remove('bw-hscroll-active');
      pauseNested(panels[prev], section);
    }

    section._bwActiveIndex = index;
    const panel = panels[index];
    if (!panel) {
      return;
    }

    panel.classList.add('bw-hscroll-active');
    if (force || panel.getAttribute('data-bw-played') !== '1') {
      playPanelAnims(panel);
      playNested(panel, section);
      panel.setAttribute('data-bw-played', '1');
    } else {
      playNested(panel, section);
    }
    panel.dispatchEvent(new CustomEvent('bw-hscroll-panel-enter', { bubbles: true }));
  }

  function addCleanup(section, fn) {
    if (!section._bwHscrollCleanup) {
      section._bwHscrollCleanup = [];
    }
    section._bwHscrollCleanup.push(fn);
  }

  function cleanupListeners(section) {
    const bag = section._bwHscrollCleanup;
    if (!bag) {
      return;
    }
    bag.forEach(function (fn) {
      try {
        fn();
      } catch (e) {
        // no-op
      }
    });
    section._bwHscrollCleanup = [];
  }

  function destroySection(section) {
    if (!section) {
      return;
    }

    cleanupListeners(section);

    if (section._bwHorizontalTween) {
      try {
        if (section._bwHorizontalTween.scrollTrigger) {
          section._bwHorizontalTween.scrollTrigger.kill();
        }
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

    const track = getTrack(section);
    if (typeof gsap !== 'undefined' && track) {
      gsap.set(track, { clearProps: 'x,xPercent,transform' });
    }
    if (track) {
      track.style.transform = '';
    }

    section.scrollTriggerSet = false;
    section._bwActiveIndex = undefined;
    const wrap = getWrapper(section);
    if (wrap && wrap.classList) {
      wrap.classList.remove('bw-hscroll-ready', 'bw-hscroll-editor', 'bw-hscroll-stacked');
    }
  }

  function setupEditor(section, wrapper, panels) {
    if (wrapper && wrapper.classList) {
      wrapper.classList.add('bw-hscroll-editor');
    }
    if (panels[0]) {
      panels[0].classList.add('bw-hscroll-active');
    }
    section.scrollTriggerSet = true;
  }

  function setupStacked(section, wrapper, panels) {
    if (wrapper && wrapper.classList) {
      wrapper.classList.add('bw-hscroll-stacked');
    }
    panels.forEach(function (panel) {
      panel.classList.add('bw-hscroll-active');
    });
    section.scrollTriggerSet = true;
  }

  function canScrollInner(panel, dir) {
    if (!panel) {
      return false;
    }
    const max = panel.scrollHeight - panel.clientHeight;
    if (max <= 1) {
      return false;
    }
    if (dir > 0) {
      return panel.scrollTop < max - 1;
    }
    return panel.scrollTop > 1;
  }

  function bindInnerScroll(section, panels) {
    const onWheel = function (e) {
      const tween = section._bwHorizontalTween;
      const st = tween && tween.scrollTrigger;
      if (!st || !st.isActive) {
        return;
      }
      const dir = e.deltaY === 0 ? 0 : (e.deltaY > 0 ? 1 : -1);
      if (!dir) {
        return;
      }
      const idx = typeof section._bwActiveIndex === 'number' ? section._bwActiveIndex : 0;
      const panel = panels[idx];
      if (!canScrollInner(panel, dir)) {
        return;
      }
      e.preventDefault();
      panel.scrollTop += e.deltaY;
    };

    section.addEventListener('wheel', onWheel, { passive: false });
    addCleanup(section, function () {
      section.removeEventListener('wheel', onWheel);
    });
  }

  function setupSnap(section, panels) {
    const rtl = isRtlContext();
    const n = panels.length;
    const track = getTrack(section);
    const target = track && track !== section ? track : panels;

    syncPanelWidth(section);
    panels.forEach(function (panel, i) {
      if (i !== 0) {
        holdPanelAnims(panel);
      }
    });

    // Lenis already eases the scroll position; a second scrub on top of it is
    // what reads as lag, so follow the scroll exactly when it is present.
    const smooth = hasSmoothScroll();

    const tweenVars = {
      ease: 'none',
      force3D: true,
      scrollTrigger: {
        trigger: section,
        pin: true,
        start: 'top top',
        scrub: smooth ? true : 0.15,
        snap: {
          snapTo: directionalSnap(1 / (n - 1)),
          duration: { min: 0.12, max: 0.32 },
          delay: 0,
          ease: 'power1.out'
        },
        end: function () {
          return '+=' + Math.max(syncPanelWidth(section) * (n - 1), 1);
        },
        invalidateOnRefresh: true,
        onEnter: function () {
          setActivePanel(section, panels, 0, true);
        },
        onEnterBack: function () {
          setActivePanel(section, panels, n - 1, true);
        },
        onUpdate: function (self) {
          const idx = Math.max(0, Math.min(n - 1, Math.round(self.progress * (n - 1))));
          setActivePanel(section, panels, idx);
        }
      }
    };

    if (track && track !== section) {
      tweenVars.x = function () {
        return trackX(section, rtl, n - 1);
      };
    } else {
      tweenVars.xPercent = (rtl ? 100 : -100) * (n - 1);
    }

    section._bwHorizontalTween = gsap.to(target, tweenVars);
    bindInnerScroll(section, panels);
    kickNestedWidgets(section);

    const holdLater = function () {
      panels.forEach(function (panel, i) {
        if (i !== section._bwActiveIndex) {
          holdPanelAnims(panel);
          pauseNested(panel, section);
        }
      });
    };
    window.setTimeout(holdLater, 400);
    window.setTimeout(function () {
      kickNestedWidgets(section);
      holdLater();
    }, 900);
  }

  function setupSection(section) {
    if (!section) {
      return;
    }

    const wrapper = getWrapper(section);
    if (isEditMode() && section.scrollTriggerSet && wrapper && wrapper.classList.contains('bw-hscroll-editor')) {
      return;
    }

    destroySection(section);

    const panels = getPanels(section);
    syncPanelWidth(section);

    if (panels[0]) {
      panels[0].classList.add('bw-hscroll-active');
    }

    if (isEditMode()) {
      setupEditor(section, wrapper, panels);
      return;
    }

    if (shouldStack(section)) {
      setupStacked(section, wrapper, panels);
      return;
    }

    if (panels.length < 2 || !ensurePlugin()) {
      section.scrollTriggerSet = true;
      return;
    }

    setupSnap(section, panels);
    section.scrollTriggerSet = true;
    if (wrapper && wrapper.classList) {
      wrapper.classList.add('bw-hscroll-ready');
    }
  }

  function collectSections(root) {
    const sections = [];
    if (!root) {
      return sections;
    }
    if (root.classList && root.classList.contains('bw-section')) {
      sections.push(root);
    }
    if (root.querySelectorAll) {
      root.querySelectorAll('.bw-section').forEach(function (section) {
        if (sections.indexOf(section) === -1) {
          sections.push(section);
        }
      });
    }
    return sections;
  }

  function initHorizontal(scope) {
    const root = scope && scope[0] ? scope[0] : (scope && scope.nodeType === 1 ? scope : document);
    collectSections(root).forEach(setupSection);
    bindResize();
    bindSmoothScroll();
  }

  let smoothWatch = null;
  function bindSmoothScroll() {
    if (isEditMode() || smoothBridged || smoothWatch) {
      return;
    }
    // The smooth-scroll plugin can boot after this widget.
    let tries = 0;
    smoothWatch = window.setInterval(function () {
      bridgeSmoothScroll();
      if (smoothBridged || ++tries > 40) {
        window.clearInterval(smoothWatch);
        smoothWatch = null;
      }
    }, 100);
  }

  let resizeBound = false;
  function bindResize() {
    if (resizeBound || isEditMode()) {
      return;
    }
    resizeBound = true;
    let timer = null;
    window.addEventListener('resize', function () {
      window.clearTimeout(timer);
      timer = window.setTimeout(function () {
        document.querySelectorAll('.bw-horizontal-section .bw-section').forEach(function (section) {
          const wrap = getWrapper(section);
          const stacked = shouldStack(section);
          const wasStacked = !!(wrap && wrap.classList && wrap.classList.contains('bw-hscroll-stacked'));
          if (stacked !== wasStacked) {
            setupSection(section);
          } else if (!stacked) {
            syncPanelWidth(section);
            if (typeof ScrollTrigger !== 'undefined' && ScrollTrigger.refresh) {
              ScrollTrigger.refresh();
            }
          }
        });
      }, 160);
    });
  }

  window.bwGetHscrollTween = function (el) {
    if (!el || !el.closest) {
      return null;
    }
    const wrap = el.closest('.bw-horizontal-section');
    if (!wrap || wrap.classList.contains('bw-hscroll-stacked') || wrap.classList.contains('bw-hscroll-editor')) {
      return null;
    }
    const section = wrap.querySelector('.bw-section');
    return (section && section._bwHorizontalTween) || null;
  };

  window.bwWhenHscrollReady = function (el, cb) {
    const node = el && el.nodeType === 1 ? el : (el && el[0]) || document;
    const wrap = node.closest ? node.closest('.bw-horizontal-section') : null;
    if (!wrap || isEditMode() || wrap.classList.contains('bw-hscroll-stacked') || wrap.classList.contains('bw-hscroll-editor')) {
      cb();
      return;
    }
    let tries = 0;
    (function tick() {
      if (window.bwGetHscrollTween(wrap) || ++tries > 50) {
        cb();
        return;
      }
      window.setTimeout(tick, 40);
    })();
  };

  window.bwInitHorizontalScrolling = function (scope) {
    initHorizontal(scope);
  };

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(
      'frontend/element_ready/b_gsap_horizontal_scrolling.default',
      function ($scope) {
        initHorizontal($scope);
      }
    );
  });
})(jQuery);
