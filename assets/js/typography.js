(function ($) {
  'use strict';

  function cleanupOrphanTriggers() {
    if (typeof ScrollTrigger === 'undefined') {
      return;
    }
    ScrollTrigger.getAll().forEach(function (st) {
      if (st.trigger && !document.documentElement.contains(st.trigger)) {
        st.kill();
      }
    });
  }

  function wrapElements(elems, wrapType, wrapClass) {
    elems.forEach(function (char) {
      if (!char || !char.parentNode) {
        return;
      }
      if (char.parentNode.classList && char.parentNode.classList.contains(wrapClass)) {
        return;
      }
      const wrapEl = document.createElement(wrapType);
      wrapEl.className = wrapClass;
      char.parentNode.appendChild(wrapEl);
      wrapEl.appendChild(char);
    });
  }

  function getSplitUnit(title) {
    return title && title.getAttribute && title.getAttribute('data-bw-split-unit') === 'words'
      ? 'words'
      : 'chars';
  }

  /**
   * Whole-animation start delay (ms to seconds). Not stagger.
   * Reads from the animate root; ignored for scrub timelines.
   */
  function getAnimDelaySec(el) {
    if (!el) {
      return 0;
    }
    var node = el;
    if ((!el.getAttribute || !el.hasAttribute('data-bw-anim-delay')) && el.closest) {
      var root = el.closest('.bw-typograpgy-animate');
      if (root) {
        node = root;
      }
    }
    if (node && node.hasAttribute && node.hasAttribute('scrub_mode')) {
      return 0;
    }
    var raw = node && node.getAttribute ? node.getAttribute('data-bw-anim-delay') : null;
    var ms = parseFloat(raw || '0');
    if (!isFinite(ms) || ms <= 0) {
      return 0;
    }
    if (ms > 5000) {
      ms = 5000;
    }
    return ms / 1000;
  }

  function withAnimDelay(toVars, el) {
    var delay = getAnimDelaySec(el);
    if (delay <= 0) {
      return toVars;
    }
    var vars = Object.assign({}, toVars);
    if (typeof vars.delay !== 'number') {
      vars.delay = delay;
    }
    return vars;
  }

  function getFxTargets(title) {
    if (!title) {
      return [];
    }
    if (getSplitUnit(title) === 'words') {
      return title.querySelectorAll('.word');
    }
    return title.querySelectorAll('.char');
  }

  function splitTextNodesIntoChars(element) {
    var walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null);
    var textNodes = [];
    while (walker.nextNode()) {
      textNodes.push(walker.currentNode);
    }
    textNodes.forEach(function (node) {
      var text = node.nodeValue || '';
      if (!text.length) {
        return;
      }
      var frag = document.createDocumentFragment();
      text.split('').forEach(function (char) {
        var span = document.createElement('span');
        span.className = 'char';
        span.setAttribute('data-char', char);
        span.textContent = char;
        frag.appendChild(span);
      });
      if (node.parentNode) {
        node.parentNode.replaceChild(frag, node);
      }
    });
  }

  function splitTextNodesIntoWords(element) {
    var walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null);
    var textNodes = [];
    while (walker.nextNode()) {
      textNodes.push(walker.currentNode);
    }
    textNodes.forEach(function (node) {
      var text = node.nodeValue || '';
      if (!text.length) {
        return;
      }
      var parts = text.split(/(\s+)/);
      var frag = document.createDocumentFragment();
      var wordIndex = 0;
      var changed = false;
      parts.forEach(function (part) {
        if (!part) {
          return;
        }
        if (/^\s+$/.test(part)) {
          frag.appendChild(document.createTextNode(part));
          return;
        }
        changed = true;
        var span = document.createElement('span');
        span.className = 'word';
        span.setAttribute('data-word', part);
        span.style.setProperty('--word-index', String(wordIndex++));
        span.textContent = part;
        frag.appendChild(span);
      });
      if (changed && node.parentNode) {
        node.parentNode.replaceChild(frag, node);
      }
    });
  }

  function scroll($scope) {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      return;
    }

    gsap.registerPlugin(ScrollTrigger);
    cleanupOrphanTriggers();

    const root = $scope && $scope[0] ? $scope[0] : document;

    // Legacy e-1 to e-8 splitter - skip SplitText types (e-9 to e-12) and static/none.
    // Pre-splitting into .char spans breaks line-based FX (Mask Rise / Clip Wipe)
    // inside Elementor flex/overflow wrappers.
    const elements = root.querySelectorAll(
      '.bw-typograpgy-animate[data-bw-splitting]:not([data-bw-no-animate]):not([data-bw-bw-scroll-e-9]):not([data-bw-bw-scroll-e-10]):not([data-bw-bw-scroll-e-11]):not([data-bw-bw-scroll-e-12]) .word:not([data-bw-split])'
    );
    elements.forEach(function (element) {
      var title = (element.closest && element.closest('.bw-typograpgy-animate')) || null;
      if (!title || title.hasAttribute('data-bw-no-animate')) {
        return;
      }
      if (getSplitUnit(title) === 'words') {
        // Flatten single legacy .word, then wrap whitespace tokens as .word units.
        if (title.childElementCount === 1 && element.parentNode === title) {
          title.innerHTML = element.innerHTML;
          splitTextNodesIntoWords(title);
          title.setAttribute('data-bw-split', '');
        }
        return;
      }
      splitTextNodesIntoChars(element);
      element.setAttribute('data-bw-split', '');
    });

    // Word-mode titles that were already flattened (no nested .word left to match above).
    root.querySelectorAll(
      '.bw-typograpgy-animate[data-bw-splitting][data-bw-split-unit="words"]:not([data-bw-no-animate]):not([data-bw-split]):not([data-bw-bw-scroll-e-9]):not([data-bw-bw-scroll-e-10]):not([data-bw-bw-scroll-e-11]):not([data-bw-bw-scroll-e-12])'
    ).forEach(function (title) {
      var onlyWord = title.querySelector(':scope > .word');
      if (onlyWord && title.childElementCount === 1) {
        title.innerHTML = onlyWord.innerHTML;
      }
      splitTextNodesIntoWords(title);
      title.setAttribute('data-bw-split', '');
    });

    const q = function (sel) {
      return [...root.querySelectorAll(sel)];
    };

    const typographyFX_1 = q('.bw-typograpgy-animate[data-bw-splitting][data-no-scrub][data-bw-bw-scroll-e-1]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_2 = q('.bw-typograpgy-animate[data-bw-splitting][data-no-scrub][data-bw-bw-scroll-e-2]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_3 = q('.bw-typograpgy-animate[data-bw-splitting][data-no-scrub][data-bw-bw-scroll-e-3]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_4 = q('.bw-typograpgy-animate[data-bw-splitting][data-no-scrub][data-bw-bw-scroll-e-4]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_5 = q('.bw-typograpgy-animate[data-bw-splitting][data-no-scrub][data-bw-bw-scroll-e-5]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_6 = q('.bw-typograpgy-animate[data-bw-splitting][data-no-scrub][data-bw-bw-scroll-e-6]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_7 = q('.bw-typograpgy-animate[data-bw-splitting][data-no-scrub][data-bw-bw-scroll-e-7]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_8 = q('.bw-typograpgy-animate[data-bw-splitting][data-no-scrub][data-bw-bw-scroll-e-8]:not([data-bw-fx]):not([data-bw-no-animate])');

    const typographyFX_1S = q('.bw-typograpgy-animate[data-bw-splitting][scrub_mode][data-bw-bw-scroll-e-1]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_2S = q('.bw-typograpgy-animate[data-bw-splitting][scrub_mode][data-bw-bw-scroll-e-2]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_3S = q('.bw-typograpgy-animate[data-bw-splitting][scrub_mode][data-bw-bw-scroll-e-3]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_4S = q('.bw-typograpgy-animate[data-bw-splitting][scrub_mode][data-bw-bw-scroll-e-4]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_5S = q('.bw-typograpgy-animate[data-bw-splitting][scrub_mode][data-bw-bw-scroll-e-5]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_6S = q('.bw-typograpgy-animate[data-bw-splitting][scrub_mode][data-bw-bw-scroll-e-6]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_7S = q('.bw-typograpgy-animate[data-bw-splitting][scrub_mode][data-bw-bw-scroll-e-7]:not([data-bw-fx]):not([data-bw-no-animate])');
    const typographyFX_8S = q('.bw-typograpgy-animate[data-bw-splitting][scrub_mode][data-bw-bw-scroll-e-8]:not([data-bw-fx]):not([data-bw-no-animate])');

    typographyFX_1.forEach(function (title) {
      const chars = getFxTargets(title);
      gsap.fromTo(chars, {
        'will-change': 'opacity, transform',
        opacity: 0,
        scale: 0.4,
        rotation: 45,
        rotationZ: function () { return gsap.utils.random(-20, 20); },
        duration: 0.3
      }, withAnimDelay({
        ease: 'power4',
        opacity: 1,
        scale: 1,
        rotation: 0,
        stagger: 0.06,
        scrollTrigger: playOnceOpts(title)
      }, title));
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_1S.forEach(function (title) {
      const chars = getFxTargets(title);
      gsap.fromTo(chars, {
        'will-change': 'opacity, transform',
        opacity: 0,
        scale: 0.4,
        rotation: 45,
        rotationZ: function () { return gsap.utils.random(-20, 20); },
        duration: 0.3
      }, {
        ease: 'power4',
        opacity: 1,
        scale: 1,
        rotation: 0,
        stagger: 0.06,
        scrollTrigger: {
          trigger: title,
          start: 'center+=20% bottom',
          end: '+=50%',
          scrub: true
        }
      });
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_2.forEach(function (title) {
      const chars = getFxTargets(title);
      gsap.fromTo(chars, {
        'will-change': 'opacity, transform',
        opacity: 0,
        yPercent: 120,
        scaleY: 2.3,
        scaleX: 0.3
      }, withAnimDelay({
        opacity: 1,
        yPercent: 1,
        scaleY: 1,
        scaleX: 1,
        ease: 'power4',
        stagger: 0.06,
        scrollTrigger: playOnceOpts(title)
      }, title));
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_2S.forEach(function (title) {
      const chars = getFxTargets(title);
      gsap.fromTo(chars, {
        'will-change': 'opacity, transform',
        opacity: 0,
        yPercent: 120,
        scaleY: 2.3,
        scaleX: 0.3
      }, {
        opacity: 1,
        yPercent: 1,
        scaleY: 1,
        scaleX: 1,
        ease: 'power4',
        stagger: 0.06,
        scrollTrigger: {
          trigger: title,
          start: 'center+=20% bottom',
          end: '+=50%',
          scrub: true
        }
      });
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_3.forEach(function (title) {
      if (getSplitUnit(title) === 'words') {
        const words = getFxTargets(title);
        words.forEach(function (word) { gsap.set(word.parentNode, { perspective: 2000 }); });
        gsap.fromTo(words, {
          'will-change': 'opacity, transform',
          opacity: 0,
          rotationX: -90,
          yPercent: 50
        }, withAnimDelay({
          ease: 'power1.inOut',
          opacity: 1,
          rotationX: 0,
          yPercent: 0,
          stagger: {
            each: 0.06,
            from: 0
          },
          scrollTrigger: playOnceOpts(title)
        }, title));
      } else {
        const words = title.querySelectorAll('.word');
        for (const word of words) {
          const chars = word.querySelectorAll('.char');
          chars.forEach(function (char) { gsap.set(char.parentNode, { perspective: 2000 }); });
          gsap.fromTo(chars, {
            'will-change': 'opacity, transform',
            opacity: 0,
            rotationX: -90,
            yPercent: 50
          }, withAnimDelay({
            ease: 'power1.inOut',
            opacity: 1,
            rotationX: 0,
            yPercent: 0,
            stagger: {
              each: 0.03,
              from: 0
            },
            scrollTrigger: playOnceOpts(word, title)
          }, title));
        }
      }
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_3S.forEach(function (title) {
      if (getSplitUnit(title) === 'words') {
        const words = getFxTargets(title);
        words.forEach(function (word) { gsap.set(word.parentNode, { perspective: 2000 }); });
        gsap.fromTo(words, {
          'will-change': 'opacity, transform',
          opacity: 0,
          rotationX: -90,
          yPercent: 50
        }, {
          ease: 'power1.inOut',
          opacity: 1,
          rotationX: 0,
          yPercent: 0,
          stagger: {
            each: 0.06,
            from: 0
          },
          scrollTrigger: {
            trigger: title,
            start: 'center+=20% bottom',
            end: '+=50%',
            scrub: true
          }
        });
      } else {
        const words = title.querySelectorAll('.word');
        for (const word of words) {
          const chars = word.querySelectorAll('.char');
          chars.forEach(function (char) { gsap.set(char.parentNode, { perspective: 2000 }); });
          gsap.fromTo(chars, {
            'will-change': 'opacity, transform',
            opacity: 0,
            rotationX: -90,
            yPercent: 50
          }, {
            ease: 'power1.inOut',
            opacity: 1,
            rotationX: 0,
            yPercent: 0,
            stagger: {
              each: 0.03,
              from: 0
            },
            scrollTrigger: {
              trigger: word,
              start: 'center+=20% bottom',
              end: '+=50%',
              scrub: true
            }
          });
        }
      }
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_4.forEach(function (title) {
      const chars = getFxTargets(title);
      wrapElements(chars, 'span', 'char-wrap');
      gsap.fromTo(chars, {
        'will-change': 'transform',
        transformOrigin: '0% 50%',
        xPercent: 105
      }, withAnimDelay({
        duration: 1,
        ease: 'expo',
        xPercent: 0,
        stagger: 0.062,
        scrollTrigger: playOnceOpts(title)
      }, title));
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_4S.forEach(function (title) {
      const chars = getFxTargets(title);
      wrapElements(chars, 'span', 'char-wrap');
      gsap.fromTo(chars, {
        'will-change': 'transform',
        transformOrigin: '0% 50%',
        xPercent: 105
      }, {
        duration: 1,
        ease: 'expo',
        xPercent: 0,
        stagger: 0.062,
        scrollTrigger: {
          trigger: title,
          start: 'center+=20% bottom',
          end: '+=50%',
          scrub: true
        }
      });
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_5.forEach(function (title) {
      const chars = getFxTargets(title);
      wrapElements(chars, 'span', 'char-wrap');
      gsap.fromTo(chars, {
        'will-change': 'transform',
        xPercent: -250,
        rotationZ: 45,
        scaleX: 6,
        transformOrigin: '100% 50%'
      }, withAnimDelay({
        duration: 1,
        ease: 'power2',
        xPercent: 0,
        rotationZ: 0,
        scaleX: 1,
        stagger: 0.06,
        scrollTrigger: playOnceOpts(title)
      }, title));
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_5S.forEach(function (title) {
      const chars = getFxTargets(title);
      wrapElements(chars, 'span', 'char-wrap');
      gsap.fromTo(chars, {
        'will-change': 'transform',
        xPercent: -250,
        rotationZ: 45,
        scaleX: 6,
        transformOrigin: '100% 50%'
      }, {
        duration: 1,
        ease: 'power2',
        xPercent: 0,
        rotationZ: 0,
        scaleX: 1,
        stagger: 0.06,
        scrollTrigger: {
          trigger: title,
          start: 'center+=20% bottom',
          end: '+=50%',
          scrub: true
        }
      });
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_6.forEach(function (title) {
      const chars = getFxTargets(title);
      chars.forEach(function (char) { gsap.set(char.parentNode, { perspective: 2000 }); });
      gsap.fromTo(chars, {
        'will-change': 'opacity, transform',
        opacity: 0,
        rotationY: 180,
        xPercent: -40,
        yPercent: 100
      }, withAnimDelay({
        ease: 'power4.inOut',
        opacity: 1,
        rotationY: 0,
        xPercent: 0,
        yPercent: 0,
        stagger: {
          each: 0.05,
          from: 0
        },
        scrollTrigger: playOnceOpts(title)
      }, title));
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_6S.forEach(function (title) {
      const chars = getFxTargets(title);
      chars.forEach(function (char) { gsap.set(char.parentNode, { perspective: 2000 }); });
      gsap.fromTo(chars, {
        'will-change': 'opacity, transform',
        opacity: 0,
        rotationY: 180,
        xPercent: -40,
        yPercent: 100
      }, {
        ease: 'power4.inOut',
        opacity: 1,
        rotationY: 0,
        xPercent: 0,
        yPercent: 0,
        stagger: {
          each: 0.05,
          from: 0
        },
        scrollTrigger: {
          trigger: title,
          start: 'center+=20% bottom',
          end: '+=50%',
          scrub: 0.9
        }
      });
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_7.forEach(function (title) {
      const words = [...title.querySelectorAll('.word')];
      const tl = gsap.timeline(withAnimDelay({
        scrollTrigger: playOnceOpts(title)
      }, title));
      if (getSplitUnit(title) === 'words') {
        for (const [wordPosition, word] of words.entries()) {
          tl.fromTo(word, {
            'will-change': 'transform',
            transformOrigin: function () {
              return !(wordPosition % 2) ? '50% 0%' : '50% 100%';
            },
            scaleY: 0,
            filter: 'blur(24px) opacity(0)'
          }, {
            ease: 'power1.inOut',
            scaleY: 1,
            filter: 'blur(0px) opacity(1)'
          }, wordPosition * 0.05);
        }
      } else {
        for (const [wordPosition, word] of words.entries()) {
          tl.fromTo(word.querySelectorAll('.char'), {
            'will-change': 'transform',
            transformOrigin: function () {
              return !(wordPosition % 2) ? '50% 0%' : '50% 100%';
            },
            scaleY: 0,
            filter: 'blur(24px) opacity(0)'
          }, {
            ease: 'power1.inOut',
            scaleY: 1,
            filter: 'blur(0px) opacity(1)',
            stagger: {
              amount: 0.3,
              from: 'left'
            }
          }, 0);
        }
      }
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_7S.forEach(function (title) {
      const words = [...title.querySelectorAll('.word')];
      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: title,
          start: 'center+=20% bottom',
          end: '+=50%',
          scrub: true
        }
      });
      if (getSplitUnit(title) === 'words') {
        for (const [wordPosition, word] of words.entries()) {
          tl.fromTo(word, {
            'will-change': 'transform',
            transformOrigin: function () {
              return !(wordPosition % 2) ? '50% 0%' : '50% 100%';
            },
            scaleY: 0,
            filter: 'blur(24px) opacity(0)'
          }, {
            ease: 'power1.inOut',
            scaleY: 1,
            filter: 'blur(0px) opacity(1)'
          }, wordPosition * 0.05);
        }
      } else {
        for (const [wordPosition, word] of words.entries()) {
          tl.fromTo(word.querySelectorAll('.char'), {
            'will-change': 'transform',
            transformOrigin: function () {
              return !(wordPosition % 2) ? '50% 0%' : '50% 100%';
            },
            scaleY: 0,
            filter: 'blur(24px) opacity(0)'
          }, {
            ease: 'power1.inOut',
            scaleY: 1,
            filter: 'blur(0px) opacity(1)',
            stagger: {
              amount: 0.3,
              from: 'left'
            }
          }, 0);
        }
      }
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_8.forEach(function (title) {
      const chars = getFxTargets(title);
      gsap.fromTo(chars, {
        'will-change': 'opacity, transform',
        opacity: 0,
        x: 0,
        y: 50,
        z: -50,
        rotationX: -80,
        filter: 'blur(8px) opacity(0)',
        transformOrigin: '50% 0%'
      }, withAnimDelay({
        opacity: 1,
        x: 0,
        y: 0,
        z: 0,
        rotationX: 0,
        filter: 'blur(0px) opacity(1)',
        ease: 'power4',
        stagger: 0.03,
        scrollTrigger: playOnceOpts(title)
      }, title));
      title.setAttribute('data-bw-fx', '');
    });

    typographyFX_8S.forEach(function (title) {
      const chars = getFxTargets(title);
      gsap.fromTo(chars, {
        'will-change': 'opacity, transform',
        opacity: 0,
        x: 0,
        y: 50,
        z: -50,
        rotationX: -80,
        filter: 'blur(8px) opacity(0)',
        transformOrigin: '50% 0%'
      }, {
        opacity: 1,
        x: 0,
        y: 0,
        z: 0,
        rotationX: 0,
        filter: 'blur(0px) opacity(1)',
        ease: 'power4',
        stagger: 0.03,
        scrollTrigger: {
          trigger: title,
          start: 'center+=20% bottom',
          end: '+=50%',
          scrub: true
        }
      });
      title.setAttribute('data-bw-fx', '');
    });

    // SplitText scroll FX (e-9 to e-12).
    runSplitScrollFX(root);
  }

  /**
   * In Elementor editor the preview iframe is often height:100% of the page
   * content, so IntersectionObserver(root:null) thinks every widget is in view
   * and plays all FX at once. Visibility must be computed against the parent
   * viewport through the iframe's frameElement (and parent scroll listeners).
   */
  function resolveTweenTrigger(targets) {
    var el = null;
    if (!targets) {
      return null;
    }
    if (targets.nodeType === 1) {
      el = targets;
    } else if (typeof targets === 'string') {
      el = document.querySelector(targets);
    } else if (targets.jquery && targets[0]) {
      el = targets[0];
    } else if (targets[0] && targets[0].nodeType === 1) {
      el = targets[0];
    } else if (typeof targets.length === 'number' && targets.length && targets[0]) {
      el = targets[0];
    }
    if (el && el.closest) {
      return el.closest('.bw-typograpgy-animate') || el.closest('.bw-scroll-text') || el.closest('.bw-typograpgy') || el;
    }
    return el;
  }

  function isVisibleInEditorCanvas(el) {
    if (!el || !el.getBoundingClientRect) {
      return false;
    }
    var rect = el.getBoundingClientRect();
    // Match frontend ScrollTrigger feel: fire near "top 85%".
    var startRatio = 0.85;

    try {
      var frame = window.frameElement;
      if (frame && window.parent && window.parent !== window) {
        var frameRect = frame.getBoundingClientRect();
        var parentH = window.parent.innerHeight || 0;
        if (parentH > 0) {
          var topInParent = frameRect.top + rect.top;
          var bottomInParent = frameRect.top + rect.bottom;
          return topInParent < parentH * startRatio && bottomInParent > parentH * 0.02;
        }
      }
    } catch (e) {
      // cross-origin parent, fall through
    }

    var vh = window.innerHeight || document.documentElement.clientHeight || 0;
    // Guard: expanded iframe (vh close to full document) would mark everything visible.
    var sh = Math.max(
      document.documentElement ? document.documentElement.scrollHeight : 0,
      document.body ? document.body.scrollHeight : 0
    );
    if (vh > 0 && sh > vh + 80) {
      return rect.top < vh * startRatio && rect.bottom > vh * 0.02;
    }
    // Expanded / non-scrolling iframe without parent access: only treat as
    // visible when near the top of the document (first screen worth).
    var fakeVh = Math.min(900, Math.max(480, (window.screen && window.screen.height) ? window.screen.height * 0.75 : 700));
    return rect.top < fakeVh * startRatio && rect.bottom > 0;
  }

  function bindEditorScroll(handler) {
    var opts = { passive: true };
    var bound = [];

    function add(target, evt) {
      if (!target || !target.addEventListener) {
        return;
      }
      target.addEventListener(evt, handler, opts);
      bound.push([target, evt]);
    }

    add(window, 'scroll');
    add(document, 'scroll');
    add(window, 'resize');
    if (document.scrollingElement) {
      add(document.scrollingElement, 'scroll');
    }

    try {
      if (window.parent && window.parent !== window) {
        add(window.parent, 'scroll');
        add(window.parent, 'resize');
        var pdoc = window.parent.document;
        add(pdoc, 'scroll');
        add(pdoc.scrollingElement, 'scroll');
        add(pdoc.documentElement, 'scroll');
        add(pdoc.body, 'scroll');
        [
          '#elementor-preview',
          '#elementor-preview-iframe',
          '.elementor-editor-preview',
          '.elementor-editor__scaffold',
          '#elementor-editor-wrapper',
          '.elementor-editor',
          '#elementor-loading'
        ].forEach(function (sel) {
          var node = pdoc.querySelector(sel);
          add(node, 'scroll');
          if (node && node.parentElement) {
            add(node.parentElement, 'scroll');
          }
        });
      }
    } catch (e) {
      // ignore
    }

    // Elementor frontend window proxy (when available inside the preview).
    try {
      if (window.elementorFrontend && elementorFrontend.elements && elementorFrontend.elements.$window) {
        elementorFrontend.elements.$window.on('scroll.bwTypoEditor', handler);
        bound.push({
          off: function () {
            elementorFrontend.elements.$window.off('scroll.bwTypoEditor', handler);
          }
        });
      }
    } catch (e2) {
      // ignore
    }

    return function unbind() {
      bound.forEach(function (pair) {
        try {
          if (pair && typeof pair.off === 'function') {
            pair.off();
            return;
          }
          pair[0].removeEventListener(pair[1], handler, opts);
        } catch (e3) {
          // ignore
        }
      });
      bound = [];
    };
  }

  function whenEditorInView(el, cb) {
    var done = false;
    var unbind = null;
    var run = function () {
      if (done) {
        return;
      }
      done = true;
      if (typeof unbind === 'function') {
        unbind();
      }
      cb();
    };

    // No trigger: do not auto-play.
    if (!el) {
      return;
    }

    var check = function () {
      if (done) {
        return;
      }
      if (isVisibleInEditorCanvas(el)) {
        run();
      }
    };

    unbind = bindEditorScroll(check);
    requestAnimationFrame(check);
    setTimeout(check, 50);
    setTimeout(check, 250);
    setTimeout(check, 800);
    // Do not auto-run on timeout; each widget must trigger on its own.
  }

  function markEditorShell() {
    if (!isElementorEditMode()) {
      return;
    }
    document.documentElement.classList.add('bw-typography-is-editor');
  }

  function withEditorGsap(fn) {
    if (!isElementorEditMode() || typeof gsap === 'undefined') {
      fn();
      return;
    }

    markEditorShell();

    var originalFromTo = gsap.fromTo;
    var originalTimeline = gsap.timeline;

    gsap.fromTo = function (targets, from, to) {
      to = Object.assign({}, to || {});
      var st = to.scrollTrigger;
      var scrubLike = !!(st && typeof st === 'object' && st.scrub);
      var triggerEl = (st && typeof st === 'object' && st.trigger)
        ? st.trigger
        : resolveTweenTrigger(targets);
      if ('scrollTrigger' in to) {
        delete to.scrollTrigger;
      }
      to.paused = true;
      if (scrubLike) {
        to.duration = 0.05;
      }
      var tw = originalFromTo.call(gsap, targets, from, to);
      whenEditorInView(triggerEl, function () {
        try {
          if (scrubLike) {
            tw.progress(1).pause();
          } else {
            tw.paused(false);
            tw.restart(true, false);
          }
        } catch (e) {
          try {
            tw.progress(1).pause();
          } catch (e2) {
            // ignore
          }
        }
      });
      return tw;
    };

    gsap.timeline = function (vars) {
      vars = Object.assign({}, vars || {});
      var st = vars.scrollTrigger;
      var scrubLike = !!(st && typeof st === 'object' && st.scrub);
      var triggerEl = (st && typeof st === 'object' && st.trigger) ? st.trigger : null;
      if ('scrollTrigger' in vars) {
        delete vars.scrollTrigger;
      }
      vars.paused = true;
      var tl = originalTimeline.call(gsap, vars);
      whenEditorInView(triggerEl, function () {
        try {
          if (scrubLike) {
            tl.progress(1).pause();
          } else {
            tl.paused(false);
            tl.restart(true, false);
          }
        } catch (e) {
          try {
            tl.progress(1).pause();
          } catch (e2) {
            // ignore
          }
        }
      });
      return tl;
    };

    try {
      fn();
    } finally {
      gsap.fromTo = originalFromTo;
      gsap.timeline = originalTimeline;
    }
  }

  /**
   * Unstick titles/wraps that never received FX so CSS pre-hide cannot leave
   * content invisible forever (GSAP missing, SplitText fail, etc.).
   * The selector skips titles that already have [data-bw-fx].
   */
  function revealPendingTypography(root) {
    var scope = root && root.querySelectorAll ? root : document;
    var titles = scope.querySelectorAll(
      '.bw-typograpgy-animate[data-bw-splitting]:not([data-bw-fx]):not([data-bw-no-animate])'
    );
    titles.forEach(function (title) {
      title.setAttribute('data-bw-fx', '');
      if (typeof gsap !== 'undefined') {
        gsap.set(title, { opacity: 1, visibility: 'visible', clearProps: 'clipPath,filter' });
      } else {
        title.style.visibility = 'visible';
        title.style.opacity = '1';
      }
    });

    var wraps = scope.querySelectorAll(
      '.bw-typograpgy-repetitive-wrap[data-bw-repetitive-anim]:not([data-bw-repetitive-ready])'
    );
    wraps.forEach(function (wrap) {
      wrap.setAttribute('data-bw-repetitive-ready', '');
      if (typeof gsap !== 'undefined') {
        gsap.set(wrap.querySelectorAll('.bw-typograpgy-repetitive'), {
          opacity: 1,
          visibility: 'visible',
          clearProps: 'clipPath,filter,transform'
        });
      } else {
        wrap.querySelectorAll('.bw-typograpgy-repetitive').forEach(function (line) {
          line.style.visibility = 'visible';
          line.style.opacity = '1';
        });
      }
    });
  }

  /**
   * Only unstick titles that never received FX. Do not force the end state on
   * paused tweens that are waiting for scroll.
   */
  function revealTypographyInEditor(root) {
    if (!isElementorEditMode()) {
      return;
    }
    setTimeout(function () {
      revealPendingTypography(root);
    }, 3500);
  }

  /**
   * Frontend fallback after the deps wait, so CSS-hidden text cannot stay stuck.
   */
  function revealTypographyOnFrontend(root) {
    if (isElementorEditMode()) {
      return;
    }
    setTimeout(function () {
      revealPendingTypography(root);
    }, 5500);
  }

  /**
   * @deprecated Delegates to revealTypographyInEditor(). Kept for the call site below.
   */
  function playTypographyFxInEditor(root) {
    revealTypographyInEditor(root);
  }

  function hasSplitText() {
    return typeof SplitText !== 'undefined';
  }

  // Once we know we're in the Elementor canvas, stay there. Early boot can
  // miss environmentMode / body classes and wrongly attach ScrollTrigger.
  var editModeSticky = false;

  /**
   * Detect Elementor editor / preview iframe reliably.
   * isEditMode() alone is not always enough across Elementor versions.
   */
  function isElementorEditMode() {
    if (editModeSticky) {
      return true;
    }
    try {
      // Set by main.php when preview assets are enqueued (most reliable).
      if (
        window.bwTypographyEnv &&
        (bwTypographyEnv.isEditor === true || bwTypographyEnv.isEditor === 1 || bwTypographyEnv.isEditor === '1')
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
      if (document.body) {
        if (
          document.body.classList.contains('elementor-editor-active') ||
          document.body.classList.contains('elementor-editor-preview') ||
          document.body.classList.contains('elementor-editor--view-page')
        ) {
          editModeSticky = true;
          return true;
        }
      }
      if (document.querySelector('.elementor-edit-area, .elementor-editor-preview')) {
        editModeSticky = true;
        return true;
      }
      // Preview document URL always carries this query arg inside the canvas iframe.
      if (/[?&]elementor-preview=/.test(String(window.location.search || ''))) {
        editModeSticky = true;
        return true;
      }
      if (/elementor-preview/.test(String(window.location.href || ''))) {
        editModeSticky = true;
        return true;
      }
      if (window.frameElement) {
        var fid = String(window.frameElement.id || '');
        var fname = String(window.frameElement.getAttribute('name') || '');
        if (fid.indexOf('elementor') !== -1 || fname.indexOf('elementor') !== -1) {
          editModeSticky = true;
          return true;
        }
      }
      // Cross-frame: parent editor app exists.
      if (window.self !== window.top) {
        try {
          if (window.top.elementor) {
            editModeSticky = true;
            return true;
          }
        } catch (e) {
          // cross-origin, ignore
        }
      }
    } catch (e) {
      return false;
    }
    return false;
  }

  /**
   * Wait until GSAP (+ SplitText when needed) is available. The first canvas
   * paint often runs before CDN SplitText has finished loading.
   */
  function whenTypographyDepsReady(root, cb) {
    var tries = 0;
    var maxTries = 100;

    function needsSplitText() {
      try {
        return !!(root && root.querySelector && root.querySelector(
          '.bw-typograpgy-animate[data-bw-bw-scroll-e-9],' +
          '.bw-typograpgy-animate[data-bw-bw-scroll-e-10],' +
          '.bw-typograpgy-animate[data-bw-bw-scroll-e-11],' +
          '.bw-typograpgy-animate[data-bw-bw-scroll-e-12]'
        ));
      } catch (e) {
        return false;
      }
    }

    function tick() {
      var gsapOk = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';
      var splitOk = !needsSplitText() || typeof SplitText !== 'undefined';
      if (gsapOk && splitOk) {
        cb();
        return;
      }
      if (++tries >= maxTries) {
        cb();
        return;
      }
      setTimeout(tick, 50);
    }

    tick();
  }

  function makeSplit(target, config) {
    if (typeof SplitText.create === 'function') {
      return SplitText.create(target, config);
    }
    return new SplitText(target, config);
  }

  /**
   * Non-scrub viewport enter options. Supports data-bw-replay for re-play on
   * every re-entry (scroll back to top, then down again).
   * In the Elementor editor ScrollTrigger is stripped by withEditorGsap; the
   * trigger element is still used for IntersectionObserver playback.
   */
  function playOnceOpts(triggerEl, replaySource) {
    var src = replaySource || triggerEl;
    var opts = {
      trigger: triggerEl,
      start: 'top 85%'
    };
    if (src && src.hasAttribute && src.hasAttribute('data-bw-replay')) {
      opts.once = false;
      opts.toggleActions = 'play none none reset';
    } else {
      opts.once = true;
    }
    return opts;
  }

  function scrubOpts(title) {
    if (title.hasAttribute('scrub_mode')) {
      return {
        trigger: title,
        start: 'center+=20% bottom',
        end: '+=50%',
        scrub: true
      };
    }
    return playOnceOpts(title);
  }

  /**
   * Merge tween "to" vars with an optional ScrollTrigger config.
   * Editor: without ScrollTrigger the tween plays on create. Scrub types jump to the end frame.
   */
  function withST(toVars, st, scrubLike) {
    var vars = Object.assign({}, toVars);
    if (st) {
      vars.scrollTrigger = st;
    } else if (isElementorEditMode() && scrubLike) {
      // Scrub can't run without scroll, so show the finished look in the canvas.
      vars.duration = 0.01;
    }
    // Whole-animation delay after enter (not scrub, not stagger).
    if (st && !st.scrub && !scrubLike) {
      var delay = getAnimDelaySec(st.trigger);
      if (delay > 0 && typeof vars.delay !== 'number') {
        vars.delay = delay;
      }
    }
    return vars;
  }

  /**
   * Editor playback is owned by withEditorGsap + IntersectionObserver.
   * Do not restart here.
   */
  function playTweenInEditor() {
    return;
  }

  function runSplitScrollFX(root) {
    if (!hasSplitText()) {
      return;
    }

    gsap.registerPlugin(SplitText, ScrollTrigger);

    var map = [
      { sel: '[data-bw-bw-scroll-e-9]:not([data-bw-fx])', fn: fxMaskRise },
      { sel: '[data-bw-bw-scroll-e-10]:not([data-bw-fx])', fn: fxWordCascade },
      { sel: '[data-bw-bw-scroll-e-11]:not([data-bw-fx])', fn: fxCharWave },
      { sel: '[data-bw-bw-scroll-e-12]:not([data-bw-fx])', fn: fxClipWipe }
    ];

    map.forEach(function (item) {
      root.querySelectorAll('.bw-typograpgy-animate' + item.sel + ':not([data-bw-no-animate])').forEach(function (title) {
        var ok = false;
        try {
          ok = item.fn(title) !== false;
        } catch (err) {
          ok = false;
        }
        // Only mark as done when SplitText actually produced targets - otherwise
        // Elementor re-init / late font load can retry.
        if (ok) {
          title.setAttribute('data-bw-fx', '');
          title.classList.add('bw-split-ready');
        }
      });
    });

    if (typeof ScrollTrigger !== 'undefined' && typeof ScrollTrigger.refresh === 'function') {
      ScrollTrigger.refresh();
    }

    // Elementor often applies kit fonts after first paint - retry line splits once fonts settle.
    if (document.fonts && typeof document.fonts.ready !== 'undefined' && document.fonts.ready.then) {
      document.fonts.ready.then(function () {
        var pending = root.querySelectorAll(
          '.bw-typograpgy-animate[data-bw-bw-scroll-e-9]:not([data-bw-fx]),' +
          '.bw-typograpgy-animate[data-bw-bw-scroll-e-10]:not([data-bw-fx]),' +
          '.bw-typograpgy-animate[data-bw-bw-scroll-e-11]:not([data-bw-fx]),' +
          '.bw-typograpgy-animate[data-bw-bw-scroll-e-12]:not([data-bw-fx])'
        );
        if (!pending.length) {
          revealTypographyInEditor(root);
          return;
        }
        withEditorGsap(function () {
          map.forEach(function (item) {
            root.querySelectorAll('.bw-typograpgy-animate' + item.sel + ':not([data-bw-no-animate])').forEach(function (title) {
              var ok = false;
              try {
                ok = item.fn(title) !== false;
              } catch (err) {
                ok = false;
              }
              if (ok) {
                title.setAttribute('data-bw-fx', '');
                title.classList.add('bw-split-ready');
              }
            });
          });
          if (typeof ScrollTrigger !== 'undefined' && typeof ScrollTrigger.refresh === 'function') {
            ScrollTrigger.refresh();
          }
        });
        revealTypographyInEditor(root);
      });
    }
  }
  function fxMaskRise(title) {
    // Prefer plain text node; unwrap legacy .word span for clean SplitText.
    prepareTitleForSplit(title);
    ensureSplitLayout(title);
    var byWords = getSplitUnit(title) === 'words';
    var split = makeSplit(title, byWords ? {
      type: 'lines,words',
      linesClass: 'bw-line',
      wordsClass: 'bw-word'
    } : {
      type: 'lines,chars',
      linesClass: 'bw-line',
      charsClass: 'bw-char'
    });
    var targets = byWords ? split.words : split.chars;
    if (!targets || !targets.length) {
      return false;
    }
    gsap.set(split.lines, { overflow: 'hidden' });
    var scrubLike = title.hasAttribute('scrub_mode');
    var tw = gsap.fromTo(targets, {
      yPercent: 110,
      opacity: 0,
      rotateZ: 4
    }, withST({
      yPercent: 0,
      opacity: 1,
      rotateZ: 0,
      ease: 'power4.out',
      stagger: byWords ? 0.06 : 0.018,
      duration: 0.9
    }, scrubOpts(title), scrubLike));
    playTweenInEditor(tw, scrubLike);
    return true;
  }

  /** e-10 - words cascade with blur */
  function fxWordCascade(title) {
    prepareTitleForSplit(title);
    ensureSplitLayout(title);
    var split = makeSplit(title, {
      type: 'words',
      wordsClass: 'bw-word'
    });
    if (!split.words || !split.words.length) {
      return false;
    }
    var scrubLike = title.hasAttribute('scrub_mode');
    var tw = gsap.fromTo(split.words, {
      y: 48,
      opacity: 0,
      filter: 'blur(12px)'
    }, withST({
      y: 0,
      opacity: 1,
      filter: 'blur(0px)',
      ease: 'power3.out',
      stagger: 0.08,
      duration: 0.85
    }, scrubOpts(title), scrubLike));
    playTweenInEditor(tw, scrubLike);
    return true;
  }

  /** e-11 - 3D char/word wave */
  function fxCharWave(title) {
    prepareTitleForSplit(title);
    ensureSplitLayout(title);
    var byWords = getSplitUnit(title) === 'words';
    var split = makeSplit(title, byWords ? {
      type: 'words',
      wordsClass: 'bw-word'
    } : {
      type: 'chars',
      charsClass: 'bw-char'
    });
    var targets = byWords ? split.words : split.chars;
    if (!targets || !targets.length) {
      return false;
    }
    gsap.set(title, { perspective: 800 });
    var scrubLike = title.hasAttribute('scrub_mode');
    var tw = gsap.fromTo(targets, {
      opacity: 0,
      y: 40,
      rotateX: -80,
      transformOrigin: '50% 50% -20'
    }, withST({
      opacity: 1,
      y: 0,
      rotateX: 0,
      ease: 'back.out(1.5)',
      stagger: {
        each: byWords ? 0.06 : 0.025,
        from: 'start'
      },
      duration: 0.75
    }, scrubOpts(title), scrubLike));
    playTweenInEditor(tw, scrubLike);
    return true;
  }

  /** e-12 - clip-path wipe across lines */
  function fxClipWipe(title) {
    prepareTitleForSplit(title);
    ensureSplitLayout(title);
    var split = makeSplit(title, {
      type: 'lines',
      linesClass: 'bw-line'
    });
    if (!split.lines || !split.lines.length) {
      return false;
    }
    var scrubLike = title.hasAttribute('scrub_mode');
    var tw = gsap.fromTo(split.lines, {
      clipPath: 'inset(0 100% 0 0)',
      opacity: 0.35,
      x: -24
    }, withST({
      clipPath: 'inset(0 0% 0 0)',
      opacity: 1,
      x: 0,
      ease: 'power4.inOut',
      stagger: 0.12,
      duration: 1.05
    }, scrubOpts(title), scrubLike));
    playTweenInEditor(tw, scrubLike);
    return true;
  }

  function prepareTitleForSplit(title) {
    // Flatten legacy single .word wrapper so SplitText sees clean text / <br>.
    var word = title.querySelector(':scope > .word');
    if (word && title.childElementCount === 1) {
      // Keep <br> (hard line breaks from the textarea); textContent would collapse them.
      title.innerHTML = word.innerHTML;
    }
  }

  /**
   * Line-based SplitText needs a real block box with a definite width.
   * Elementor Advanced (flex / transform / overflow on the widget wrapper)
   * often collapses that box so Mask Rise / Clip Wipe produce zero lines.
   */
  function ensureSplitLayout(title) {
    title.style.display = 'block';
    title.style.width = '100%';
    title.style.maxWidth = '100%';
    title.style.boxSizing = 'border-box';
    // Scroll Text keeps pre-line (textarea hard breaks); other titles use normal wrap.
    if (!title.closest('.bw-scroll-text')) {
      title.style.whiteSpace = 'normal';
    }
    // Force layout before SplitText measures lines.
    void title.offsetWidth;
  }

  /**
   * "Text Movement" (Scroll Text) - scrub-driven from/to tween on the whole
   * block. Config travels as a data attribute rather than an inline <script>
   * because Elementor injects re-rendered widget markup with innerHTML, which
   * never executes inline scripts (so the editor showed no movement at all).
   */
  function movementVars(part) {
    var vars = {};
    if (!part || typeof part !== 'object') {
      return null;
    }
    ['x', 'y'].forEach(function (key) {
      if (part[key] !== undefined && part[key] !== '') {
        vars[key] = part[key];
      }
    });
    ['opacity', 'rotation'].forEach(function (key) {
      var num = parseFloat(part[key]);
      if (!isNaN(num)) {
        vars[key] = num;
      }
    });
    var duration = parseFloat(part.duration);
    vars.duration = isNaN(duration) ? 0.4 : duration;
    return Object.keys(vars).length > 1 ? vars : null;
  }

  function textMovement($scope) {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      return;
    }

    gsap.registerPlugin(ScrollTrigger);
    var root = $scope && $scope[0] ? $scope[0] : document;

    root.querySelectorAll('.bw-typograpgy-wrap[data-bw-move]:not([data-bw-move-ready])').forEach(function (el) {
      var config;
      try {
        config = JSON.parse(el.getAttribute('data-bw-move'));
      } catch (e) {
        return;
      }
      if (!config) {
        return;
      }

      var from = movementVars(config.from);
      var to = movementVars(config.to);
      if (!from && !to) {
        return;
      }

      var tl = gsap.timeline({
        scrollTrigger: {
          trigger: el,
          start: 'center bottom',
          end: 'center top',
          scrub: true
        }
      });
      if (from) {
        tl.from(el, from);
      }
      if (to) {
        tl.to(el, to);
      }
      el.setAttribute('data-bw-move-ready', '');
    });
  }

  function repetitiveAnim($scope) {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      return;
    }

    gsap.registerPlugin(ScrollTrigger);
    const root = $scope && $scope[0] ? $scope[0] : document;
    const wraps = root.querySelectorAll('.bw-typograpgy-repetitive-wrap[data-bw-repetitive-anim]:not([data-bw-repetitive-ready])');

    wraps.forEach(function (wrap) {
      const lines = wrap.querySelectorAll('.bw-typograpgy-repetitive');
      if (!lines.length) {
        return;
      }

      const type = wrap.getAttribute('data-bw-repetitive-anim') || 'fade-up';
      const playOnce = wrap.getAttribute('data-bw-repetitive-once') !== 'no';
      const stBase = {
        trigger: wrap,
        start: 'top 85%',
        once: playOnce,
        toggleActions: playOnce ? 'play none none none' : 'play reverse play reverse'
      };

      // SplitText repetitive styles (opt-in).
      var splitTypes = {
        'mask-up': 1,
        'chars-stagger': 1,
        'words-blur': 1,
        'line-wave': 1,
        'kinetic-slam': 1,
        'kinetic-scatter': 1,
        'kinetic-skew': 1,
        'kinetic-flip': 1,
        'kinetic-echo': 1,
        'kinetic-stack': 1,
        'kinetic-bloom': 1,
        'kinetic-slice': 1
      };
      if (hasSplitText() && splitTypes[type]) {
        gsap.registerPlugin(SplitText);
        runRepetitiveSplit(wrap, lines, type, stBase);
        wrap.setAttribute('data-bw-repetitive-ready', '');
        wrap.classList.add('bw-split-ready');
        return;
      }

      const from = { opacity: 0, force3D: true };
      const to = {
        opacity: 1,
        duration: 0.7,
        ease: 'power3.out',
        force3D: true,
        stagger: type === 'stagger' ? 0.12 : 0.04,
        scrollTrigger: stBase
      };

      if (type === 'fade-up') {
        from.y = 36;
        to.y = 0;
      } else if (type === 'scale') {
        from.scale = 0.92;
        to.scale = 1;
      } else if (type === 'stagger') {
        from.y = 24;
        to.y = 0;
      }

      gsap.fromTo(lines, from, to);
      wrap.setAttribute('data-bw-repetitive-ready', '');
    });

    requestAnimationFrame(function () {
      ScrollTrigger.refresh();
    });
  }

  function runRepetitiveSplit(wrap, lines, type, stBase) {
    var lineList = Array.prototype.slice.call(lines);

    // Stack Focus: whole lines bloom from the middle row (reference: solid center + echoes).
    if (type === 'kinetic-stack') {
      wrap.classList.add('bw-kinetic-apple');
      gsap.fromTo(
        lineList,
        { opacity: 0, y: 28, filter: 'blur(6px)' },
        {
          opacity: 1,
          y: 0,
          filter: 'blur(0px)',
          ease: 'power4.out',
          duration: 0.95,
          stagger: { each: 0.1, from: 'center' },
          force3D: true,
          scrollTrigger: stBase
        }
      );
      return;
    }

    // Echo Trail: ghost copies behind each line, then SplitText chars from center.
    if (type === 'kinetic-echo') {
      wrap.classList.add('bw-kinetic-apple');
      var tl = gsap.timeline({ scrollTrigger: stBase });
      var allChars = [];

      lineList.forEach(function (line) {
        var shell = document.createElement('div');
        shell.className = 'bw-kinetic-echo';
        line.parentNode.insertBefore(shell, line);
        shell.appendChild(line);
        line.classList.add('bw-kinetic-echo-main');

        var layers = [];
        for (var i = 4; i >= 1; i--) {
          var layer = line.cloneNode(true);
          layer.classList.remove('bw-unique', 'bw-kinetic-echo-main');
          layer.classList.add('bw-kinetic-echo-layer');
          layer.setAttribute('aria-hidden', 'true');
          shell.insertBefore(layer, line);
          gsap.set(layer, {
            position: 'absolute',
            left: 0,
            top: 0,
            opacity: 0,
            x: 0,
            y: 0,
            zIndex: i
          });
          layers.push({ el: layer, depth: i });
        }

        layers.forEach(function (item) {
          tl.fromTo(
            item.el,
            { opacity: 0, y: 0, x: 0 },
            {
              opacity: 0.08 + item.depth * 0.07,
              y: -item.depth * 6,
              x: item.depth * 2,
              duration: 0.9,
              ease: 'power3.out'
            },
            0.04 * (5 - item.depth)
          );
        });

        var split = makeSplit(line, { type: 'chars', charsClass: 'bw-char' });
        if (split.chars && split.chars.length) {
          allChars = allChars.concat(Array.from(split.chars));
        }
      });

      if (allChars.length) {
        tl.fromTo(
          allChars,
          { opacity: 0, y: 18, scale: 0.96 },
          {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 0.85,
            ease: 'expo.out',
            stagger: { each: 0.018, from: 'center' },
            force3D: true
          },
          0.12
        );
      }
      return;
    }

    var targets = [];
    var splits = [];
    var needsLineMask = type === 'mask-up' || type === 'kinetic-skew' || type === 'kinetic-slice';

    lineList.forEach(function (line) {
      var splitType = 'chars';
      var classMap = { charsClass: 'bw-char' };
      if (type === 'words-blur') {
        splitType = 'words';
        classMap = { wordsClass: 'bw-word' };
      } else if (type === 'line-wave' || type === 'mask-up' || type === 'kinetic-skew' || type === 'kinetic-slice') {
        classMap = { charsClass: 'bw-char', linesClass: 'bw-line' };
        if (type === 'mask-up' || type === 'kinetic-skew') {
          splitType = 'lines,chars';
        } else if (type === 'kinetic-slice') {
          splitType = 'chars';
        }
      }

      var split = makeSplit(line, Object.assign({ type: splitType }, classMap));
      splits.push(split);

      if (needsLineMask && split.lines) {
        gsap.set(split.lines, { overflow: 'hidden' });
      }
      if (type === 'kinetic-slice') {
        gsap.set(line, { overflow: 'hidden' });
      }

      if (type === 'words-blur' && split.words) {
        targets = targets.concat(Array.from(split.words));
      } else if (split.chars) {
        targets = targets.concat(Array.from(split.chars));
      }
    });

    if (!targets.length) {
      return;
    }

    wrap.classList.add('bw-kinetic-apple');

    var centerStagger = { each: 0.02, from: 'center' };

    if (type === 'mask-up') {
      gsap.fromTo(targets, { yPercent: 110, opacity: 0 }, {
        yPercent: 0,
        opacity: 1,
        ease: 'power4.out',
        duration: 0.9,
        stagger: { each: 0.014, from: 'center' },
        scrollTrigger: stBase
      });
    } else if (type === 'chars-stagger') {
      // Chars stagger from center.
      gsap.fromTo(targets, { y: 22, opacity: 0 }, {
        y: 0,
        opacity: 1,
        ease: 'expo.out',
        duration: 0.75,
        stagger: centerStagger,
        force3D: true,
        scrollTrigger: stBase
      });
    } else if (type === 'words-blur') {
      gsap.fromTo(targets, { y: 28, opacity: 0, filter: 'blur(8px)' }, {
        y: 0,
        opacity: 1,
        filter: 'blur(0px)',
        ease: 'power3.out',
        duration: 0.85,
        stagger: { each: 0.055, from: 'center' },
        scrollTrigger: stBase
      });
    } else if (type === 'line-wave') {
      gsap.fromTo(targets, { y: 36, opacity: 0 }, {
        y: 0,
        opacity: 1,
        ease: 'power4.out',
        duration: 0.8,
        stagger: centerStagger,
        force3D: true,
        scrollTrigger: stBase
      });
    } else if (type === 'kinetic-slam') {
      // Soft Slam - gentle scale from center, no tilt.
      gsap.fromTo(targets, {
        opacity: 0,
        scale: 1.28,
        transformOrigin: '50% 50%'
      }, {
        opacity: 1,
        scale: 1,
        ease: 'expo.out',
        duration: 0.9,
        stagger: centerStagger,
        force3D: true,
        scrollTrigger: stBase
      });
    } else if (type === 'kinetic-scatter') {
      // Soft Gather - only slight vertical offset from center (no random/crooked).
      gsap.fromTo(targets, {
        opacity: 0,
        y: 24,
        scale: 0.94
      }, {
        opacity: 1,
        y: 0,
        scale: 1,
        ease: 'power4.out',
        duration: 0.95,
        stagger: centerStagger,
        force3D: true,
        scrollTrigger: stBase
      });
    } else if (type === 'kinetic-skew') {
      // Center Rise (key kept for BC) - masked rise, no skew.
      gsap.fromTo(targets, {
        yPercent: 105,
        opacity: 0
      }, {
        yPercent: 0,
        opacity: 1,
        ease: 'expo.out',
        duration: 0.88,
        stagger: centerStagger,
        force3D: true,
        scrollTrigger: stBase
      });
    } else if (type === 'kinetic-flip') {
      // Soft Focus - blur to sharp from center.
      gsap.fromTo(targets, {
        opacity: 0,
        y: 14,
        filter: 'blur(10px)',
        scale: 1.04
      }, {
        opacity: 1,
        y: 0,
        filter: 'blur(0px)',
        scale: 1,
        ease: 'power3.out',
        duration: 1,
        stagger: centerStagger,
        force3D: true,
        scrollTrigger: stBase
      });
    } else if (type === 'kinetic-bloom') {
      // Bloom - expand from center letter.
      gsap.fromTo(targets, {
        opacity: 0,
        scale: 0.6,
        transformOrigin: '50% 50%'
      }, {
        opacity: 1,
        scale: 1,
        ease: 'back.out(1.2)',
        duration: 0.85,
        stagger: { each: 0.025, from: 'center' },
        force3D: true,
        scrollTrigger: stBase
      });
    } else if (type === 'kinetic-slice') {
      // Slice Reveal - open from center.
      gsap.fromTo(targets, {
        opacity: 0,
        scaleX: 0,
        transformOrigin: '50% 50%'
      }, {
        opacity: 1,
        scaleX: 1,
        ease: 'power4.inOut',
        duration: 0.9,
        stagger: { each: 0.03, from: 'center' },
        force3D: true,
        scrollTrigger: stBase
      });
    }
  }

  // Always register Elementor hooks - GSAP may load after this file.
  function initTypographyScope($scope) {
    var root = $scope && $scope[0] ? $scope[0] : document;
    markEditorShell();
    whenTypographyDepsReady(root, function () {
      var gsapOk = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';
      if (!gsapOk) {
        // CSS hides pending FX, so reveal immediately if GSAP never arrived.
        revealPendingTypography(root);
        return;
      }
      withEditorGsap(function () {
        scroll($scope);
        textMovement($scope);
        repetitiveAnim($scope);
      });
      // Safety net: never leave clipped / invisible text (editor + frontend).
      revealTypographyInEditor(root);
      revealTypographyOnFrontend(root);
    });
  }

  // Editor preview / MutationObserver re-init (see black-widgets-preview.js).
  window.bwInitTypographyAnimate = function (scope) {
    var $scope = scope && scope.jquery ? scope : jQuery(scope || document);
    initTypographyScope($scope);
  };

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/b_typography.default', function ($scope) {
      initTypographyScope($scope);
    });
    // Black Scroll Text uses the same FX markup + typography.js.
    elementorFrontend.hooks.addAction('frontend/element_ready/b_scroll_text.default', function ($scope) {
      initTypographyScope($scope);
    });
  });

  /**
   * Document-wide sweep. Every step is guarded by a "already done" attribute,
   * so this is purely a safety net for the cases where
   * frontend/element_ready never reaches us - widgets printed inside a loop
   * grid / popup / saved template, or a theme that loads Elementor's frontend
   * bundle after this file. Without it the FX silently never start.
   */
  function sweepDocument() {
    initTypographyScope(jQuery(document));
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', sweepDocument);
  } else {
    sweepDocument();
  }
  $(window).on('load', sweepDocument);

})(jQuery);
