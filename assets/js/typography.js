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
      // Wrap in place - appendChild would move every wrapper to the end of the
      // parent and strand the whitespace text nodes between words at the front.
      char.parentNode.insertBefore(wrapEl, char);
      wrapEl.appendChild(char);
    });
  }

  function getSplitUnit(title) {
    return title && title.getAttribute && title.getAttribute('data-bw-split-unit') === 'words'
      ? 'words'
      : 'chars';
  }

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
      return [...root.querySelectorAll(sel)].filter(function (el) {
        return !shouldDeferHscroll(el);
      });
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
      // No [data-bw-fx] means no tween owns the split children, so a half-built
      // split can leave them at a from-state (opacity 0, shifted, or clipped).
      var parts = title.querySelectorAll('.bw-line, .bw-line-mask, .bw-word, .bw-char, .char, .word');
      if (typeof gsap !== 'undefined') {
        gsap.set(title, { opacity: 1, visibility: 'visible', clearProps: 'clipPath,filter' });
        if (parts.length) {
          gsap.set(parts, {
            opacity: 1,
            visibility: 'visible',
            clearProps: 'clipPath,filter,transform'
          });
        }
      } else {
        title.style.visibility = 'visible';
        title.style.opacity = '1';
        parts.forEach(function (part) {
          part.style.visibility = 'visible';
          part.style.opacity = '1';
          part.style.clipPath = 'none';
          part.style.transform = 'none';
        });
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

  // Latch edit mode once detected: early boot may miss environmentMode and
  // body classes, which would wrongly attach ScrollTrigger.
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
   * Line-based split where SplitText owns the clipping wrapper (mask:'lines')
   * and re-splits itself on font load / resize (autoSplit).
   *
   * Hand-rolling the mask with overflow:hidden plus a negative bottom margin
   * left a line box only a few pixels tall, so a failed or late tween showed a
   * horizontal slice of the glyphs instead of the text. autoSplit also rebuilds
   * the tween, so the previous line elements can no longer stay frozen at the
   * from-state (invisible text) after a re-split.
   *
   * @param {Element}  title      Title element to split.
   * @param {Object}   config     SplitText config (must include lines in type).
   * @param {Function} buildTween Receives the split instance, returns a tween.
   * @return {boolean} True when a tween was built.
   */
  function splitWithMask(title, config, buildTween) {
    var built = false;
    var previous = null;
    var completed = false;

    function build(split) {
      // A re-split leaves the old ScrollTrigger behind; drop it so repeated
      // resizes cannot stack triggers on the same title.
      if (previous) {
        if (previous.scrollTrigger) {
          previous.scrollTrigger.kill();
        }
        previous.kill();
        previous = null;
      }
      var tween = buildTween(split);
      if (tween) {
        previous = tween;
        built = true;
        if (completed) {
          // Already played once - a resize re-split must not replay it.
          tween.progress(1);
        } else {
          tween.eventCallback('onComplete', function () {
            completed = true;
          });
        }
      }
      return tween;
    }

    if (typeof SplitText.create === 'function') {
      try {
        SplitText.create(title, Object.assign({}, config, {
          mask: 'lines',
          autoSplit: true,
          onSplit: build
        }));
      } catch (e) {
        built = false;
      }
      return built;
    }

    // GSAP < 3.13 has no mask / autoSplit / onSplit - split once and mask by hand.
    var legacy;
    try {
      legacy = new SplitText(title, config);
    } catch (e) {
      return false;
    }
    if (!legacy || !legacy.lines || !legacy.lines.length) {
      return false;
    }
    gsap.set(legacy.lines, { overflow: 'hidden' });
    return !!build(legacy);
  }

  function hscrollTweenFor(el) {
    if (typeof window.bwGetHscrollTween === 'function') {
      return window.bwGetHscrollTween(el);
    }
    if (!el || !el.closest) {
      return null;
    }
    var wrap = el.closest('.bw-horizontal-section');
    if (!wrap || wrap.classList.contains('bw-hscroll-stacked') || wrap.classList.contains('bw-hscroll-editor')) {
      return null;
    }
    var section = wrap.querySelector('.bw-section');
    return (section && section._bwHorizontalTween) || null;
  }

  function shouldDeferHscroll(el) {
    if (!el || !el.closest || isElementorEditMode()) {
      return false;
    }
    var wrap = el.closest('.bw-horizontal-section');
    if (!wrap || wrap.classList.contains('bw-hscroll-stacked') || wrap.classList.contains('bw-hscroll-editor')) {
      return false;
    }
    return !hscrollTweenFor(el);
  }

  function withHscroll(opts, el) {
    var tween = hscrollTweenFor(el);
    if (!tween || !opts) {
      return opts;
    }
    opts.containerAnimation = tween;
    if (opts.scrub) {
      opts.start = 'left right';
      opts.end = 'right left';
    } else {
      opts.start = 'left 75%';
      opts.end = 'right 20%';
    }
    return opts;
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
      start: 'top 85%',
      // Splitting text changes the element's height, so start/end must be
      // recomputed on refresh instead of keeping the pre-split values.
      invalidateOnRefresh: true
    };
    if (src && src.hasAttribute && src.hasAttribute('data-bw-replay')) {
      opts.once = false;
      opts.toggleActions = 'play none none reset';
    } else {
      opts.once = true;
    }
    return withHscroll(opts, triggerEl);
  }

  function scrubOpts(title) {
    if (title.hasAttribute('scrub_mode')) {
      return withHscroll({
        trigger: title,
        start: 'center+=20% bottom',
        end: '+=50%',
        scrub: true,
        invalidateOnRefresh: true
      }, title);
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

  var SPLIT_FX_MAP = [
    { sel: '[data-bw-bw-scroll-e-9]:not([data-bw-fx])', fn: fxMaskRise },
    { sel: '[data-bw-bw-scroll-e-10]:not([data-bw-fx])', fn: fxWordCascade },
    { sel: '[data-bw-bw-scroll-e-11]:not([data-bw-fx])', fn: fxCharWave },
    { sel: '[data-bw-bw-scroll-e-12]:not([data-bw-fx])', fn: fxClipWipe }
  ];

  var SPLIT_FX_PENDING = SPLIT_FX_MAP.map(function (item) {
    return '.bw-typograpgy-animate' + item.sel;
  }).join(',');

  function runSplitFxPass(root) {
    var built = 0;
    SPLIT_FX_MAP.forEach(function (item) {
      root.querySelectorAll('.bw-typograpgy-animate' + item.sel + ':not([data-bw-no-animate])').forEach(function (title) {
        if (shouldDeferHscroll(title)) {
          return;
        }
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
          built++;
        }
      });
    });
    return built;
  }

  function refreshScrollTriggers() {
    if (typeof ScrollTrigger !== 'undefined' && typeof ScrollTrigger.refresh === 'function') {
      ScrollTrigger.refresh();
    }
  }

  // One font retry per root. runSplitScrollFX() is re-entered from
  // DOMContentLoaded, window.load and element_ready, and a fonts.ready handler
  // per call meant a burst of refreshes that fired triggers all at once.
  var fontRetryRoots = typeof WeakSet === 'function' ? new WeakSet() : null;

  function scheduleFontRetry(root) {
    if (!document.fonts || !document.fonts.ready || !document.fonts.ready.then) {
      return;
    }
    if (fontRetryRoots) {
      if (fontRetryRoots.has(root)) {
        return;
      }
      fontRetryRoots.add(root);
    }
    // Elementor often applies kit fonts after first paint - retry line splits once fonts settle.
    document.fonts.ready.then(function () {
      if (root.querySelectorAll(SPLIT_FX_PENDING).length) {
        withEditorGsap(function () {
          if (runSplitFxPass(root)) {
            refreshScrollTriggers();
          }
        });
      }
      revealTypographyInEditor(root);
    });
  }

  function runSplitScrollFX(root) {
    if (!hasSplitText()) {
      return;
    }

    gsap.registerPlugin(SplitText, ScrollTrigger);

    if (runSplitFxPass(root)) {
      refreshScrollTriggers();
    }

    scheduleFontRetry(root);
  }
  function fxMaskRise(title) {
    // Prefer plain text node; unwrap legacy .word span for clean SplitText.
    prepareTitleForSplit(title);
    ensureSplitLayout(title);
    var byWords = getSplitUnit(title) === 'words';
    var scrubLike = title.hasAttribute('scrub_mode');

    return splitWithMask(title, byWords ? {
      type: 'lines,words',
      linesClass: 'bw-line',
      wordsClass: 'bw-word'
    } : {
      type: 'lines,chars',
      linesClass: 'bw-line',
      charsClass: 'bw-char'
    }, function (split) {
      var targets = byWords ? split.words : split.chars;
      if (!targets || !targets.length) {
        return null;
      }
      return gsap.fromTo(targets, {
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
    });
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
    var scrubLike = title.hasAttribute('scrub_mode');

    return splitWithMask(title, {
      type: 'lines',
      linesClass: 'bw-line'
    }, function (split) {
      if (!split.lines || !split.lines.length) {
        return null;
      }
      return gsap.fromTo(split.lines, {
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
    });
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
      if (shouldDeferHscroll(el)) {
        return;
      }
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
        scrollTrigger: withHscroll({
          trigger: el,
          start: 'center bottom',
          end: 'center top',
          scrub: true
        }, el)
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

  /**
   * Repetitive fade presets. Every line starts on the Unique line's side and
   * settles outward, so `base` is the travel of the first neighbour and `step`
   * how much further each additional line away from the Unique line travels.
   */
  var REPETITIVE_FADE_PRESETS = {
    // Pure opacity: lines light up one by one, nothing moves.
    fade: { base: 0, step: 0, duration: 0.6, each: 0.13, ease: 'power1.out' },
    // Every line slides the same short distance out of the Unique line.
    'fade-outward': { base: 30, step: 0, duration: 0.7, each: 0.07, ease: 'power3.out' },
    // Slow unfold with heavy overlap: outer lines travel a little further.
    'fade-soft': { base: 10, step: 12, duration: 1.2, each: 0.05, ease: 'power2.out' },
    // Snap: almost no travel and the lines land nearly together.
    'fade-tight': { base: 6, step: 0, duration: 0.3, each: 0.025, ease: 'power2.out' },
    // Wide unfold, clearly one pair at a time.
    'fade-deep': { base: 16, step: 26, duration: 1, each: 0.18, ease: 'power4.out' }
  };

  function resolveRepetitiveFadeType(type) {
    return REPETITIVE_FADE_PRESETS[type] ? type : 'fade-outward';
  }

  function uniqueRepetitiveIndex(lines) {
    var origin = -1;
    for (var i = 0; i < lines.length; i++) {
      if (lines[i].classList.contains('bw-unique')) {
        origin = i;
        break;
      }
    }
    if (origin < 0) {
      origin = Math.floor((lines.length - 1) / 2);
    }
    return origin;
  }

  function repetitiveAnim($scope) {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      return;
    }

    gsap.registerPlugin(ScrollTrigger);
    const root = $scope && $scope[0] ? $scope[0] : document;
    const wraps = root.querySelectorAll('.bw-typograpgy-repetitive-wrap[data-bw-repetitive-anim]:not([data-bw-repetitive-ready])');

    wraps.forEach(function (wrap) {
      if (shouldDeferHscroll(wrap)) {
        return;
      }
      const lines = wrap.querySelectorAll('.bw-typograpgy-repetitive');
      if (!lines.length) {
        return;
      }

      const type = resolveRepetitiveFadeType(wrap.getAttribute('data-bw-repetitive-anim') || 'fade-outward');
      const preset = REPETITIVE_FADE_PRESETS[type];
      const playOnce = wrap.getAttribute('data-bw-repetitive-once') !== 'no';
      const stBase = withHscroll({
        trigger: wrap,
        start: 'top 85%',
        once: playOnce,
        toggleActions: playOnce ? 'play none none none' : 'play reverse play reverse'
      }, wrap);

      var lineList = Array.prototype.slice.call(lines);
      var origin = uniqueRepetitiveIndex(lineList);
      var tl = gsap.timeline({ scrollTrigger: stBase });

      lineList.forEach(function (line, i) {
        var distance = Math.abs(i - origin);
        var travel = distance ? preset.base + preset.step * (distance - 1) : 0;
        // Lines above start lower and lines below start higher, so both settle
        // away from the Unique line instead of sliding in the same direction.
        var fromY = i < origin ? travel : -travel;

        tl.fromTo(
          line,
          { opacity: 0, y: fromY, force3D: true },
          {
            opacity: 1,
            y: 0,
            duration: preset.duration,
            ease: preset.ease,
            force3D: true
          },
          distance * preset.each
        );
      });

      wrap.setAttribute('data-bw-repetitive-ready', '');
    });

    requestAnimationFrame(function () {
      ScrollTrigger.refresh();
    });
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