(function () {
	'use strict';

	var pluginsRegistered = false;

	function registerPlugins() {
		if (pluginsRegistered) {
			return true;
		}
		if (typeof gsap === 'undefined') {
			return false;
		}
		if (typeof SplitText === 'undefined' || typeof ScrollTrigger === 'undefined') {
			return false;
		}
		gsap.registerPlugin(SplitText, ScrollTrigger);
		if (typeof ScrollTrigger.config === 'function') {
			ScrollTrigger.config({ limitCallbacks: true });
		}
		pluginsRegistered = true;
		return true;
	}

	function parseNumber(value, fallback) {
		var n = parseFloat(value);
		return isNaN(n) ? fallback : n;
	}

	function readCssNumber(root, prop, fallback) {
		if (!root || !window.getComputedStyle) {
			return fallback;
		}
		var raw = window.getComputedStyle(root).getPropertyValue(prop);
		return parseNumber(raw, fallback);
	}

	function readDataNumber(root, attr, cssProp, fallback) {
		// Prefer CSS vars - Elementor selector controls update them without remount.
		var fromCss = readCssNumber(root, cssProp, NaN);
		if (!isNaN(fromCss)) {
			return fromCss;
		}
		if (cssProp && cssProp.indexOf('--bw-') === 0) {
			fromCss = readCssNumber(root, '--mws-ew-' + cssProp.slice(5), NaN);
			if (!isNaN(fromCss)) {
				return fromCss;
			}
		}
		var raw = root.getAttribute(attr);
		if (raw !== null && raw !== '') {
			return parseNumber(raw, fallback);
		}
		return fallback;
	}

	function isElementorEditMode() {
		try {
			return !!(
				window.elementorFrontend &&
				elementorFrontend.isEditMode &&
				elementorFrontend.isEditMode()
			);
		} catch (e) {
			return false;
		}
	}

	function createEnterTrigger(vars) {
		try {
			return ScrollTrigger.create(vars);
		} catch (e) {
			return null;
		}
	}

	function readMotionOpts(root, defaults, triggerMode) {
		return {
			duration: readDataNumber(root, 'data-duration', '--bw-at-duration', defaults.duration),
			stagger: readDataNumber(root, 'data-stagger', '--bw-at-stagger', defaults.stagger),
			delay: readDataNumber(root, 'data-delay', '--bw-at-delay', triggerMode === 'load' ? 0.15 : 0),
			scrub: readDataNumber(root, 'data-scrub', '--bw-at-scrub', 1)
		};
	}

	function prefersReducedMotion() {
		try {
			return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
		} catch (e) {
			return false;
		}
	}

	function canAnimate() {
		if (prefersReducedMotion()) {
			return false;
		}
		return typeof gsap !== 'undefined' && registerPlugins();
	}

	function prepareSplitBox(el) {
		if (!el) {
			return;
		}
		el.style.display = 'block';
		el.style.width = '100%';
		el.style.maxWidth = '';
		el.style.boxSizing = 'border-box';
		void el.offsetWidth;
	}

	function watchBoxWidth(el, onChange) {
		if (!el || typeof onChange !== 'function') {
			return function () {};
		}

		var lastWidth = el.clientWidth;
		var timer = 0;

		function check() {
			var w = el.clientWidth;
			if (Math.abs(w - lastWidth) < 1) {
				return;
			}
			lastWidth = w;
			onChange();
		}

		function onResize() {
			clearTimeout(timer);
			timer = setTimeout(check, 200);
		}

		window.addEventListener('resize', onResize);
		var ro = null;
		if (typeof ResizeObserver === 'function') {
			ro = new ResizeObserver(onResize);
			ro.observe(el);
		}

		return function () {
			window.removeEventListener('resize', onResize);
			clearTimeout(timer);
			if (ro) {
				ro.disconnect();
			}
		};
	}

	var fontsReady = false;

	function whenFontsReady(cb) {
		if (fontsReady) {
			cb();
			return;
		}
		var settled = false;
		function finish() {
			if (settled) {
				return;
			}
			settled = true;
			fontsReady = true;
			cb();
		}
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(finish).catch(finish);
			// Editor iframes can leave fonts.ready pending forever.
			setTimeout(finish, 1000);
			return;
		}
		finish();
	}

	function getAnimDefaults(type) {
		var map = {
			'from-bottom': { split: 'chars', duration: 0.9, stagger: 0.028, ease: 'expo.out' },
			'from-top': { split: 'chars', duration: 0.9, stagger: 0.024, ease: 'power4.out' },
			'from-left': { split: 'words', duration: 0.75, stagger: 0.09, ease: 'power3.out' },
			'from-right': { split: 'words', duration: 0.75, stagger: 0.09, ease: 'power3.out' },
			fade: { split: 'words', duration: 1.1, stagger: 0.07, ease: 'sine.out' },
			blur: { split: 'chars', duration: 1, stagger: 0.02, ease: 'power2.out' },
			flip3d: { split: 'chars', duration: 0.8, stagger: 0.035, ease: 'back.out(1.7)' },
			mask: { split: 'lines', duration: 1, stagger: 0.12, ease: 'power4.out' }
		};
		return map[type] || map['from-bottom'];
	}

	function resolveSplitType(animType, requested) {
		if (requested && requested !== 'auto') {
			return requested;
		}
		return getAnimDefaults(animType).split;
	}

	function bakeTitleTextTransform(title) {
		if (!title) {
			return;
		}

		var transform = title.getAttribute('data-bw-at-transform');
		if (!transform) {
			try {
				transform = window.getComputedStyle(title).textTransform || 'none';
			} catch (e) {
				transform = 'none';
			}
			title.setAttribute('data-bw-at-transform', transform);
		}

		title.style.textTransform = 'none';
		if (transform !== 'uppercase' && transform !== 'lowercase' && transform !== 'capitalize') {
			return;
		}

		var walker = document.createTreeWalker(title, NodeFilter.SHOW_TEXT, null);
		var nodes = [];
		while (walker.nextNode()) {
			nodes.push(walker.currentNode);
		}

		nodes.forEach(function (node) {
			var text = node.nodeValue || '';
			if (!text) {
				return;
			}
			if (transform === 'uppercase') {
				node.nodeValue = text.toLocaleUpperCase();
				return;
			}
			if (transform === 'lowercase') {
				node.nodeValue = text.toLocaleLowerCase();
				return;
			}
			node.nodeValue = text.replace(/([^\s])([^\s]*)/g, function (_, first, rest) {
				return first.toLocaleUpperCase() + rest.toLocaleLowerCase();
			});
		});
	}

	function lockSplitCharCase(split) {
		if (!split || !split.chars || !split.chars.length) {
			return;
		}
		var i;
		for (i = 0; i < split.chars.length; i++) {
			if (split.chars[i]) {
				split.chars[i].style.textTransform = 'none';
			}
		}
	}

	function createSplit(title, splitType) {
		var config;
		var split;

		bakeTitleTextTransform(title);

		if (splitType === 'lines') {
			config = {
				type: 'lines',
				linesClass: 'bw-at__line',
				mask: 'lines'
			};
		} else if (splitType === 'words') {
			config = {
				type: 'words',
				wordsClass: 'bw-at__word'
			};
		} else {
			config = {
				type: 'words,chars',
				wordsClass: 'bw-at__word-holder',
				charsClass: 'bw-at__char'
			};
		}

		try {
			if (typeof SplitText.create === 'function') {
				split = SplitText.create(title, config);
			} else {
				split = new SplitText(title, config);
			}
		} catch (e) {
			return null;
		}

		lockSplitCharCase(split);
		return split;
	}

	function getTargets(split, splitType) {
		if (!split) {
			return [];
		}
		if (splitType === 'lines') {
			return split.lines || [];
		}
		if (splitType === 'words') {
			return split.words || [];
		}
		return split.chars || [];
	}

	function getFromTo(animType) {
		switch (animType) {
			case 'from-top':
				return {
					from: { yPercent: -120, opacity: 0 },
					to: { yPercent: 0, opacity: 1 }
				};
			case 'from-left':
				return {
					from: { xPercent: -60, opacity: 0 },
					to: { xPercent: 0, opacity: 1 }
				};
			case 'from-right':
				return {
					from: { xPercent: 60, opacity: 0 },
					to: { xPercent: 0, opacity: 1 }
				};
			case 'fade':
				return {
					from: { opacity: 0 },
					to: { opacity: 1 }
				};
			case 'blur':
				return {
					from: { opacity: 0, filter: 'blur(6px)', yPercent: 30 },
					to: { opacity: 1, filter: 'blur(0px)', yPercent: 0 }
				};
			case 'flip3d':
				return {
					from: { opacity: 0, rotateX: -100, transformOrigin: '50% 100%', z: 40 },
					to: { opacity: 1, rotateX: 0 }
				};
			case 'mask':
				return {
					from: { yPercent: 110 },
					to: { yPercent: 0 }
				};
			case 'from-bottom':
			default:
				return {
					from: { yPercent: 120, opacity: 0 },
					to: { yPercent: 0, opacity: 1 }
				};
		}
	}

	/**
	 * Paint a continuous gradient across SplitText nodes.
	 * Parent background-clip does not show through nested spans.
	 * Reads all layout first, then writes styles (avoids thrashing).
	 */
	function syncGradientSplit(root, title, targets) {
		if (!root || !title || !targets || !targets.length) {
			return;
		}
		if (!root.classList.contains('bw-at--gradient') && !root.classList.contains('mws-ew-at--gradient')) {
			return;
		}

		var titleRect = title.getBoundingClientRect();
		var titleW = Math.max(1, titleRect.width);
		var sizeFactor = 2.6;
		var bgSize = titleW * sizeFactor + 'px 100%';
		var offsets = new Array(targets.length);
		var i;
		var el;
		var r;

		for (i = 0; i < targets.length; i++) {
			el = targets[i];
			if (!el) {
				offsets[i] = 0;
				continue;
			}
			r = el.getBoundingClientRect();
			offsets[i] = r.left - titleRect.left;
			el._bwGradOffset = offsets[i];
		}

		for (i = 0; i < targets.length; i++) {
			el = targets[i];
			if (!el) {
				continue;
			}
			el.style.backgroundImage = 'var(--bw-at-grad-image, var(--mws-ew-at-grad-image))';
			el.style.backgroundSize = bgSize;
			el.style.backgroundPosition = -offsets[i] + 'px 0';
			el.style.backgroundRepeat = 'no-repeat';
			el.style.webkitBackgroundClip = 'text';
			el.style.backgroundClip = 'text';
			el.style.color = 'transparent';
			el.style.webkitTextFillColor = 'transparent';
		}
	}

	function assignVars() {
		var out = {};
		var i;
		var source;
		var key;
		for (i = 0; i < arguments.length; i++) {
			source = arguments[i];
			if (!source) {
				continue;
			}
			for (key in source) {
				if (Object.prototype.hasOwnProperty.call(source, key)) {
					out[key] = source[key];
				}
			}
		}
		return out;
	}

	function buildTween(title, animType, split, splitType, opts, root) {
		var targets = getTargets(split, splitType);
		var duration = opts.duration;
		var stagger = opts.stagger;
		var ease = opts.ease;
		var delay = opts.delay;
		var pair = getFromTo(animType);

		if (!targets.length) {
			return gsap.to({}, { duration: 0 });
		}

		syncGradientSplit(root, title, targets);
		gsap.set(targets, pair.from);

		if (animType === 'mask' && root && (root.classList.contains('bw-at--gradient') || root.classList.contains('mws-ew-at--gradient'))) {
			var titleW = Math.max(1, title.getBoundingClientRect().width);
			// Offsets already cached by syncGradientSplit - avoid a second gBCR pass.
			var sweep = { pos: titleW * 1.5 };
			var tl = gsap.timeline({ delay: delay });
			tl.to(targets, {
				yPercent: 0,
				duration: duration,
				ease: ease,
				stagger: stagger
			});
			tl.to(
				sweep,
				{
					pos: -titleW * 0.5,
					duration: Math.max(duration * 1.4, 1.2),
					ease: 'power2.inOut',
					onUpdate: function () {
						targets.forEach(function (el) {
							el.style.backgroundPosition =
								sweep.pos - (el._bwGradOffset || 0) + 'px 0';
						});
					}
				},
				0.1
			);
			return tl;
		}

		return gsap.to(
			targets,
			assignVars(pair.to, {
				duration: duration,
				ease: ease,
				stagger: stagger,
				delay: delay
			})
		);
	}

	function showFinalState(root, title) {
		root.classList.add('is-ready', 'is-complete');
		if (title) {
			title.style.visibility = 'visible';
		}
	}

	function cleanupInstance(root) {
		if (!root) {
			return;
		}

		if (typeof root._bwAtCancelGsap === 'function') {
			root._bwAtCancelGsap();
			delete root._bwAtCancelGsap;
		}

		if (typeof root._bwAtCleanup === 'function') {
			root._bwAtCleanup();
		}

		delete root._bwAtCleanup;
		delete root.dataset.bwAtInit;
		delete root.dataset.bwAtPending;
		root.removeAttribute('data-bw-at-tries');
		root.classList.remove('is-ready', 'is-complete', 'is-playing');
		if (root.querySelector) {
			var atTitle = root.querySelector('.bw-at__title, .mws-ew-at__title');
			if (atTitle) {
				atTitle.removeAttribute('data-bw-at-transform');
				atTitle.style.textTransform = '';
			}
		}
	}

	function initInstance(root) {
		if (!root || root.dataset.bwAtInit === '1') {
			return;
		}

		var title = root.querySelector('.bw-at__title, .mws-ew-at__title');
		if (!title) {
			return;
		}

		root.dataset.bwAtInit = '1';

		// Capture server-rendered (kses'd) markup so replay keeps <br>/<strong>/<b>/<hr>.
		var originalHTML = title.innerHTML;

		var animType = root.getAttribute('data-anim') || 'from-bottom';
		var splitRequested = root.getAttribute('data-split') || 'auto';
		var triggerMode = root.getAttribute('data-trigger') || 'scroll';
		var scrollMode = root.getAttribute('data-scroll-mode') || 'once';
		var replayOnBack = root.getAttribute('data-replay-back') === 'yes';
		// inview / footer-safe: fire as soon as the widget enters the viewport.
		var start =
			triggerMode === 'inview'
				? 'top bottom'
				: root.getAttribute('data-start') || 'top 75%';
		var defaults = getAnimDefaults(animType);
		var easeAttr = root.getAttribute('data-ease') || 'auto';
		var ease = !easeAttr || easeAttr === 'auto' ? defaults.ease : easeAttr;
		var splitType = resolveSplitType(animType, splitRequested);

		function currentOpts(forceNoDelay) {
			var motion = readMotionOpts(root, defaults, triggerMode);
			return {
				duration: motion.duration,
				stagger: motion.stagger,
				delay: forceNoDelay ? 0 : motion.delay,
				ease: ease,
				scrub: motion.scrub
			};
		}

		var splitInstance = null;
		var activeTween = null;
		var scrollTrigger = null;
		var hasPlayed = false;
		var isBusy = false;
		var destroyed = false;
		var detachResize = null;

		function revertSplit() {
			if (activeTween) {
				activeTween.kill();
				activeTween = null;
			}

			if (splitInstance) {
				try {
					if (typeof splitInstance.revert === 'function') {
						splitInstance.revert();
					}
				} catch (e) {
					/* no-op */
				}
				splitInstance = null;
			}

			title.innerHTML = originalHTML;
			title.style.width = '';
			title.style.maxWidth = '';
			if (typeof gsap !== 'undefined') {
				gsap.set(title, { clearProps: 'transform,opacity,filter,backgroundPosition,width' });
			}
		}

		function ensureSplit() {
			if (!splitInstance) {
				prepareSplitBox(title);
				splitInstance = createSplit(title, splitType);
			}
			return splitInstance;
		}

		function markComplete() {
			isBusy = false;
			root.classList.remove('is-playing');
			root.classList.add('is-complete');
		}

		function play(force) {
			if (destroyed) {
				return null;
			}

			if (!force && scrollMode === 'once' && hasPlayed) {
				return null;
			}

			// Allow restart during an in-flight tween only for repeat / forced replay.
			if (isBusy && !force && scrollMode !== 'repeat') {
				return null;
			}

			isBusy = true;
			root.classList.add('is-playing');
			root.classList.remove('is-complete');

			if (activeTween) {
				activeTween.kill();
				activeTween = null;
			}

			// Reuse SplitText DOM on replay; only create when missing.
			ensureSplit();
			if (!splitInstance) {
				showFinalState(root, title);
				hasPlayed = true;
				markComplete();
				return null;
			}

			var targets = getTargets(splitInstance, splitType);
			if (targets.length) {
				gsap.killTweensOf(targets);
			}

			activeTween = buildTween(title, animType, splitInstance, splitType, currentOpts(!!force), root);
			// Reveal only after from-state exists (CSS hides until .is-ready).
			title.style.visibility = 'visible';
			root.classList.add('is-ready');

			if (activeTween && typeof activeTween.eventCallback === 'function') {
				activeTween.eventCallback('onComplete', function () {
					hasPlayed = true;
					markComplete();
				});
			} else {
				hasPlayed = true;
				markComplete();
			}

			return activeTween;
		}

		function setupScrub() {
			var opts = currentOpts(true);
			var scrubAmount = opts.scrub;
			var pair = getFromTo(animType);

			revertSplit();
			prepareSplitBox(title);

			splitInstance = createSplit(title, splitType);
			if (!splitInstance) {
				showFinalState(root, title);
				return;
			}
			var targets = getTargets(splitInstance, splitType);
			syncGradientSplit(root, title, targets);
			gsap.set(targets, pair.from);
			// Reveal only after from-state is applied (CSS hides until .is-ready).
			title.style.visibility = 'visible';
			root.classList.add('is-ready');

			var toVars = assignVars(pair.to, {
				ease: 'none',
				stagger: opts.stagger
			});

			var tl;
			try {
				tl = gsap.timeline({
					scrollTrigger: {
						trigger: root,
						start: start,
						end: 'bottom top',
						scrub: scrubAmount > 0 ? scrubAmount : true
						// No invalidateOnRefresh - static from/to; refresh must not jump siblings.
					}
				});

				tl.to(targets, toVars);
			} catch (e) {
				revertSplit();
				showFinalState(root, title);
				return;
			}

			activeTween = tl;
			scrollTrigger = tl.scrollTrigger || null;

			tl.eventCallback('onComplete', function () {
				root.classList.add('is-complete');
			});
		}

		function setupReverse() {
			revertSplit();
			prepareSplitBox(title);

			splitInstance = createSplit(title, splitType);
			if (!splitInstance) {
				showFinalState(root, title);
				return;
			}

			var tween = buildTween(title, animType, splitInstance, splitType, currentOpts(true), root);
			if (!tween) {
				showFinalState(root, title);
				return;
			}
			tween.pause(0);
			// Reveal only after from-state is applied (CSS hides until .is-ready).
			title.style.visibility = 'visible';
			root.classList.add('is-ready');

			try {
				scrollTrigger = ScrollTrigger.create({
					trigger: root,
					start: start,
					toggleActions: 'play none none reverse',
					animation: tween
				});
			} catch (e) {
				revertSplit();
				showFinalState(root, title);
				return;
			}

			activeTween = tween;
			tween.eventCallback('onComplete', function () {
				hasPlayed = true;
				root.classList.add('is-complete');
			});
		}

		function rebuildForWidth() {
			if (destroyed) {
				return;
			}

			if (scrollTrigger && typeof scrollTrigger.kill === 'function') {
				scrollTrigger.kill();
			}
			scrollTrigger = null;

			var complete = root.classList.contains('is-complete') || hasPlayed;
			var useScroll = triggerMode === 'scroll' || triggerMode === 'inview';

			if (useScroll && scrollMode === 'scrub') {
				setupScrub();
				return;
			}

			if (useScroll && scrollMode === 'reverse') {
				setupReverse();
				return;
			}

			revertSplit();
			prepareSplitBox(title);
			ensureSplit();
			if (!splitInstance) {
				showFinalState(root, title);
				return;
			}

			if (complete) {
				activeTween = buildTween(title, animType, splitInstance, splitType, currentOpts(true), root);
				if (activeTween && typeof activeTween.progress === 'function') {
					activeTween.progress(1);
				}
				title.style.visibility = 'visible';
				root.classList.add('is-ready', 'is-complete');
				root.classList.remove('is-playing');
				hasPlayed = true;
				isBusy = false;
				return;
			}

			title.style.visibility = 'hidden';
			if (isElementorEditMode() || triggerMode === 'load' || !useScroll) {
				play(false);
				return;
			}
			setupScrollCallbacks();
		}

		function setupScrollCallbacks() {
			// Hide until first play to avoid flash of unsplit text mid-scroll.
			title.style.visibility = 'hidden';

			var playOnce = scrollMode === 'once' && !replayOnBack;

			scrollTrigger = createEnterTrigger({
				trigger: root,
				start: start,
				once: playOnce,
				onEnter: function () {
					if (scrollMode === 'once' && hasPlayed) {
						return;
					}
					play(false);
				},
				onEnterBack: function () {
					if (!replayOnBack) {
						return;
					}
					play(true);
				}
			});
		}

		function startEngine() {
			if (destroyed) {
				return;
			}

			if (prefersReducedMotion()) {
				showFinalState(root, title);
				return;
			}

			// GSAP / SplitText may still be loading.
			if (!canAnimate()) {
				var tries = parseInt(root.getAttribute('data-bw-at-tries') || '0', 10);
				if (tries < 25) {
					root.setAttribute('data-bw-at-tries', String(tries + 1));
					root.dataset.bwAtPending = '1';
					var atTimer = setTimeout(function () {
						delete root.dataset.bwAtPending;
						delete root._bwAtCancelGsap;
						if (!destroyed) {
							startEngine();
						}
					}, 160);
					root._bwAtCancelGsap = function () {
						clearTimeout(atTimer);
					};
					return;
				}
				showFinalState(root, title);
				return;
			}

			root.removeAttribute('data-bw-at-tries');
			delete root.dataset.bwAtPending;

			if (!detachResize) {
				detachResize = watchBoxWidth(root, rebuildForWidth);
			}

			// Editor: preview immediately so duration/stagger/delay remounts are visible.
			if (isElementorEditMode() || triggerMode === 'load') {
				title.style.visibility = 'hidden';
				play(false);
				return;
			}

			// scroll + inview (footer / page-end) share the same scroll engine.
			if (triggerMode !== 'scroll' && triggerMode !== 'inview') {
				title.style.visibility = 'hidden';
				play(false);
				return;
			}

			if (scrollMode === 'scrub') {
				setupScrub();
				return;
			}

			if (scrollMode === 'reverse') {
				setupReverse();
				return;
			}

			// once | repeat - createEnterTrigger arms when already past start.
			setupScrollCallbacks();
		}

		root._bwAtCleanup = function () {
			destroyed = true;
			isBusy = false;

			if (detachResize) {
				detachResize();
				detachResize = null;
			}

			if (scrollTrigger && typeof scrollTrigger.kill === 'function') {
				scrollTrigger.kill();
			}
			scrollTrigger = null;

			revertSplit();
			title.style.visibility = '';
			root.classList.remove('is-ready', 'is-complete', 'is-playing');
		};

		whenFontsReady(function () {
			if (!destroyed) {
				startEngine();
			}
		});
	}

	function readLrMotion(root) {
		return {
			duration: readDataNumber(root, 'data-duration', '--bw-lr-duration', 1),
			stagger: readDataNumber(root, 'data-stagger', '--bw-lr-stagger', 0.09)
		};
	}

	function applyLineMasks(split) {
		if (!split) {
			return;
		}
		var i;
		var el;
		if (split.masks) {
			for (i = 0; i < split.masks.length; i++) {
				el = split.masks[i];
				if (!el) {
					continue;
				}
				el.style.overflow = 'hidden';
				el.style.display = 'block';
			}
		}
		if (split.lines) {
			for (i = 0; i < split.lines.length; i++) {
				el = split.lines[i];
				if (el) {
					el.style.display = 'block';
				}
			}
		}
	}

	/**
	 * After SplitText, drop the measure spacer and keep line 1 shifted with
	 * margin. nowrap prevents an already-measured line from wrapping again.
	 */
	function applyLrFirstLineShift(title, split, indent) {
		var lines = split && split.lines ? split.lines : [];
		var masks = split && split.masks ? split.masks : [];
		var i;
		var el;

		if (title) {
			title.style.textIndent = '';
			title.style.setProperty('--bw-lr-indent', '0px');
			title.style.setProperty('--mws-ew-lr-indent', '0px');
		}

		for (i = 0; i < lines.length; i++) {
			el = lines[i];
			if (!el) {
				continue;
			}
			el.style.textIndent = '0';
			el.style.whiteSpace = 'nowrap';
		}

		for (i = 0; i < masks.length; i++) {
			el = masks[i];
			if (!el) {
				continue;
			}
			el.style.textIndent = '0';
			el.style.marginInlineStart = '';
		}

		if (indent > 0) {
			el = masks[0] || lines[0];
			if (el) {
				el.style.marginInlineStart = indent + 'px';
			}
		}
	}

	/**
	 * Reserve the eyebrow before SplitText measures by setting --bw-lr-indent
	 * (title::before float). Only line 1 wraps short; later lines stay full width.
	 * Also sets is-stacked / is-compact on narrow columns.
	 */
	function prepareLrLayout(root, title, content, eyebrow) {
		var stackedMq = !!(window.matchMedia && window.matchMedia('(max-width: 767px)').matches);
		var compact = stackedMq || innerIsCompact(root, content);
		var stacked = stackedMq;
		var indent = 0;

		if (root) {
			root.classList.toggle('is-compact', compact);
			root._bwLrIndent = 0;
		}

		if (!stackedMq && content && eyebrow) {
			void content.offsetWidth;
			indent = eyebrow.offsetWidth;
			if (indent <= 0 || content.clientWidth - indent < 96) {
				stacked = true;
				indent = 0;
			}
		}

		if (root) {
			root.classList.toggle('is-stacked', stacked);
			if (stacked) {
				root.classList.add('is-compact');
			}
			root._bwLrIndent = indent;
		}

		if (title) {
			title.style.textIndent = '';
			title.style.setProperty('--bw-lr-indent', indent > 0 ? indent + 'px' : '0px');
			title.style.setProperty('--mws-ew-lr-indent', indent > 0 ? indent + 'px' : '0px');
		}

		return stacked;
	}

	function innerIsCompact(root, content) {
		var inner = root && root.querySelector ? root.querySelector('.bw-lr__inner, .mws-ew-lr__inner') : null;
		if (inner && inner.clientWidth > 0 && inner.clientWidth < 420) {
			return true;
		}
		return !!(content && content.clientWidth > 0 && content.clientWidth < 280);
	}

	function isRtlContext(el) {
		var node = el;
		while (node && node.nodeType === 1) {
			try {
				if (window.getComputedStyle(node).direction === 'rtl') {
					return true;
				}
			} catch (e) {
				return false;
			}
			node = node.parentElement;
		}
		return false;
	}

	function wrapWordsAsLines(split) {
		// SplitText type:"lines" detects wraps with an LTR left-edge test, so RTL collapses to one line.
		var words = split && split.words ? split.words : [];
		if (!words.length) {
			return;
		}

		var groups = [];
		var current = [];
		var lastTop = null;
		var i;
		var j;
		var word;
		var top;
		var next;
		var mask;
		var line;
		var lines = [];
		var masks = [];

		for (i = 0; i < words.length; i++) {
			word = words[i];
			top = word.getBoundingClientRect().top;
			if (lastTop !== null && top > lastTop + 2) {
				groups.push(current);
				current = [];
			}
			current.push(word);
			lastTop = top;
		}
		if (current.length) {
			groups.push(current);
		}

		for (i = 0; i < groups.length; i++) {
			current = groups[i];
			if (!current.length || !current[0].parentNode) {
				continue;
			}
			mask = document.createElement('span');
			mask.className = 'bw-at__line-mask bw-lr__line-mask';
			mask.setAttribute('aria-hidden', 'true');
			mask.style.display = 'block';
			mask.style.overflow = 'hidden';
			line = document.createElement('span');
			line.className = 'bw-at__line bw-lr__line';
			line.setAttribute('aria-hidden', 'true');
			line.style.display = 'block';
			current[0].parentNode.insertBefore(mask, current[0]);
			mask.appendChild(line);
			for (j = 0; j < current.length; j++) {
				word = current[j];
				next = word.nextSibling;
				line.appendChild(word);
				if (next && next.nodeType === 3) {
					line.appendChild(next);
				}
			}
			lines.push(line);
			masks.push(mask);
		}

		split.lines = lines;
		split.masks = masks;
	}

	function syncEyebrowHeight(eyebrow, split) {
		var lrRoot = eyebrow && eyebrow.closest ? eyebrow.closest('.bw-lr, .mws-ew-lr') : null;
		var stacked = !!(
			(lrRoot && lrRoot.classList.contains('is-stacked')) ||
			(window.matchMedia && window.matchMedia('(max-width: 767px)').matches)
		);

		if (stacked) {
			if (lrRoot) {
				lrRoot.style.removeProperty('--bw-lr-first-h');
				lrRoot.style.removeProperty('--mws-ew-lr-first-h');
			}
			if (eyebrow) {
				eyebrow.style.height = '';
			}
			return;
		}

		if (!split || !split.lines || !split.lines[0]) {
			return;
		}

		var h = split.lines[0].offsetHeight;
		if (h > 0 && lrRoot) {
			lrRoot.style.setProperty('--bw-lr-first-h', h + 'px');
			lrRoot.style.setProperty('--mws-ew-lr-first-h', h + 'px');
		}
		if (eyebrow) {
			eyebrow.style.height = '';
		}
	}

	function setLrHidden(inner, index, eyebrow, lines) {
		if (index) {
			gsap.set(index, { y: 16, opacity: 0 });
		}
		if (eyebrow) {
			gsap.set(eyebrow, { y: 16, opacity: 0 });
		}
		if (lines && lines.length) {
			gsap.set(lines, { yPercent: 110, opacity: 0 });
		}
	}

	function buildLrTimeline(root, inner, index, eyebrow, split) {
		var motion = readLrMotion(root);
		var lines = split && split.lines ? split.lines : [];

		setLrHidden(inner, index, eyebrow, lines);

		var tl = gsap.timeline({
			paused: true,
			defaults: { ease: 'power4.out' },
			onStart: function () {
				root.classList.add('is-playing');
				root.classList.remove('is-complete');
			},
			onComplete: function () {
				root.classList.remove('is-playing');
				root.classList.add('is-complete');
			}
		});

		if (index) {
			tl.to(index, { y: 0, opacity: 1, duration: 0.6 }, 0);
		}
		if (eyebrow) {
			tl.to(eyebrow, { y: 0, opacity: 1, duration: 0.6 }, index ? '<0.05' : 0);
		}
		if (lines.length) {
			tl.to(
				lines,
				{
					yPercent: 0,
					opacity: 1,
					duration: motion.duration,
					stagger: motion.stagger
				},
				'-=0.25'
			);
		}

		return tl;
	}

	function showLrFinal(root, title, inner, index, eyebrow) {
		root.classList.add('is-ready', 'is-complete');
		root.classList.remove('is-playing');
		if (title) {
			title.style.visibility = 'visible';
		}
		if (typeof gsap === 'undefined') {
			return;
		}
		if (inner) {
			gsap.set(inner, { clearProps: 'transform,opacity' });
		}
		if (index) {
			gsap.set(index, { clearProps: 'transform,opacity' });
		}
		if (eyebrow) {
			gsap.set(eyebrow, { clearProps: 'transform,opacity' });
		}
	}

	function canAutoSplit() {
		if (typeof SplitText === 'undefined' || typeof SplitText.create !== 'function') {
			return false;
		}
		if (typeof gsap === 'undefined' || !gsap.version) {
			return true;
		}
		var parts = String(gsap.version).split('.');
		var major = parseInt(parts[0], 10) || 0;
		var minor = parseInt(parts[1], 10) || 0;
		return major > 3 || (major === 3 && minor >= 13);
	}

	function cleanupLineReveal(root) {
		if (!root) {
			return;
		}

		if (typeof root._bwLrCancelGsap === 'function') {
			root._bwLrCancelGsap();
			delete root._bwLrCancelGsap;
		}

		if (typeof root._bwLrCleanup === 'function') {
			root._bwLrCleanup();
		}

		delete root._bwLrCleanup;
		delete root._bwLrIndent;
		delete root.dataset.bwLrInit;
		delete root.dataset.bwLrPending;
		root.removeAttribute('data-bw-lr-tries');
		root.classList.remove('is-ready', 'is-complete', 'is-playing', 'is-stacked', 'is-compact');
	}

	function initLineReveal(root) {
		if (!root || root.dataset.bwLrInit === '1') {
			return;
		}

		var title = root.querySelector('.bw-lr__title, .mws-ew-lr__title');
		if (!title) {
			return;
		}

		root.dataset.bwLrInit = '1';

		var originalHTML = title.innerHTML;
		var inner = root.querySelector('.bw-lr__inner, .mws-ew-lr__inner');
		var index = root.querySelector('.bw-lr__index, .mws-ew-lr__index');
		var eyebrow = root.querySelector('.bw-lr__eyebrow, .mws-ew-lr__eyebrow');
		var start = root.getAttribute('data-start') || 'top 75%';
		var replay = root.getAttribute('data-replay') === 'yes';

		var splitInstance = null;
		var activeTween = null;
		var scrollTrigger = null;
		var hasPlayed = false;
		var destroyed = false;
		var detachResize = null;

		function revertSplit() {
			if (activeTween) {
				activeTween.kill();
				activeTween = null;
			}

			if (splitInstance) {
				try {
					if (typeof splitInstance.revert === 'function') {
						splitInstance.revert();
					}
				} catch (e) {
					/* no-op */
				}
				splitInstance = null;
			}

			title.innerHTML = originalHTML;
			title.style.textIndent = '';
			title.style.width = '';
			title.style.maxWidth = '';
			title.style.removeProperty('--bw-lr-indent');
			title.style.removeProperty('--mws-ew-lr-indent');
			if (eyebrow) {
				eyebrow.style.height = '';
			}
			root.style.removeProperty('--bw-lr-first-h');
			root.style.removeProperty('--mws-ew-lr-first-h');
			if (typeof gsap !== 'undefined') {
				if (inner) {
					gsap.set(inner, { clearProps: 'transform,opacity,visibility' });
				}
				if (index) {
					gsap.set(index, { clearProps: 'transform,opacity,visibility' });
				}
				if (eyebrow) {
					gsap.set(eyebrow, { clearProps: 'transform,opacity,visibility' });
				}
				gsap.set(title, { clearProps: 'transform,opacity,visibility,width' });
			}
		}

		function markReady() {
			title.style.visibility = 'visible';
			root.classList.add('is-ready');
		}

		function finishSplit(self) {
			if (isRtlContext(title)) {
				wrapWordsAsLines(self);
			}
			applyLineMasks(self);
			applyLrFirstLineShift(title, self, root._bwLrIndent || 0);
			syncEyebrowHeight(eyebrow, self);
		}

		// autoSplit is intentionally OFF. We drive every re-split ourselves in
		// attachResize()/rebuildSplit() so prepareLrLayout() (indent + stacked
		// classes) always runs before the split. Relying on GSAP's built-in
		// autoSplit re-split on resize was the responsive bug: it re-broke the
		// lines with a stale text-indent and never refreshed is-stacked/compact.
		function createModernSplit() {
			try {
				if (isRtlContext(title)) {
					return SplitText.create(title, {
						type: 'words',
						wordsClass: 'bw-at__word',
						autoSplit: false
					});
				}
				return SplitText.create(title, {
					type: 'lines',
					linesClass: 'bw-at__line bw-lr__line',
					mask: 'lines',
					autoSplit: false
				});
			} catch (e) {
				return null;
			}
		}

		// Single creation path shared by the initial split and every rebuild:
		// modern SplitText.create when available (GSAP >= 3.13 / 3.15), else legacy.
		function createSplitInstance() {
			var instance = null;
			if (canAutoSplit()) {
				instance = createModernSplit();
			}
			if (!instance) {
				instance = createLegacySplit();
			}
			return instance;
		}

		function createLegacySplit() {
			try {
				if (isRtlContext(title)) {
					return new SplitText(title, {
						type: 'words',
						wordsClass: 'bw-at__word'
					});
				}
				return new SplitText(title, {
					type: 'lines',
					linesClass: 'bw-at__line bw-lr__line'
				});
			} catch (e) {
				return null;
			}
		}

		// Recompute layout (indent + is-stacked/compact) before re-splitting, then
		// rebuild. Used on every resize for both the modern and legacy paths.
		function rebuildSplit(keepComplete) {
			var complete = keepComplete || root.classList.contains('is-complete') || (hasPlayed && !replay);

			revertSplit();
			prepareSplitBox(title);
			prepareLrLayout(root, title, title.parentElement, eyebrow);
			splitInstance = createSplitInstance();
			if (!splitInstance) {
				showLrFinal(root, title, inner, index, eyebrow);
				return;
			}
			finishSplit(splitInstance);
			activeTween = buildLrTimeline(root, inner, index, eyebrow, splitInstance);
			markReady();
			if (complete && activeTween) {
				activeTween.progress(1);
				hasPlayed = true;
			}
		}

		function attachResize() {
			var box = root.querySelector('.bw-lr__inner, .mws-ew-lr__inner') || root;
			return watchBoxWidth(box, function () {
				if (!destroyed) {
					rebuildSplit(false);
				}
			});
		}

		function playNow() {
			if (activeTween) {
				activeTween.restart();
				hasPlayed = true;
				return;
			}
			showLrFinal(root, title, inner, index, eyebrow);
		}

		function setupScroll() {
			if (isElementorEditMode()) {
				playNow();
				return;
			}

			scrollTrigger = createEnterTrigger({
				trigger: root,
				start: start,
				once: !replay,
				onEnter: function () {
					playNow();
				},
				onLeaveBack: function () {
					if (!replay) {
						return;
					}
					if (activeTween) {
						activeTween.pause(0);
					}
					setLrHidden(inner, index, eyebrow, splitInstance && splitInstance.lines ? splitInstance.lines : []);
					root.classList.remove('is-playing', 'is-complete');
					hasPlayed = false;
				}
			});

			if (!scrollTrigger) {
				playNow();
			}
		}

		function startEngine() {
			if (destroyed) {
				return;
			}

			if (prefersReducedMotion()) {
				showLrFinal(root, title, inner, index, eyebrow);
				return;
			}

			if (!canAnimate()) {
				var tries = parseInt(root.getAttribute('data-bw-lr-tries') || '0', 10);
				if (tries < 25) {
					root.setAttribute('data-bw-lr-tries', String(tries + 1));
					root.dataset.bwLrPending = '1';
					var lrTimer = setTimeout(function () {
						delete root.dataset.bwLrPending;
						delete root._bwLrCancelGsap;
						if (!destroyed) {
							startEngine();
						}
					}, 160);
					root._bwLrCancelGsap = function () {
						clearTimeout(lrTimer);
					};
					return;
				}
				showLrFinal(root, title, inner, index, eyebrow);
				return;
			}

			root.removeAttribute('data-bw-lr-tries');
			delete root.dataset.bwLrPending;

			title.style.visibility = 'hidden';
			prepareSplitBox(title);
			prepareLrLayout(root, title, title.parentElement, eyebrow);

			try {
				splitInstance = createSplitInstance();
				if (!splitInstance) {
					showLrFinal(root, title, inner, index, eyebrow);
					return;
				}

				finishSplit(splitInstance);
				activeTween = buildLrTimeline(root, inner, index, eyebrow, splitInstance);
				// Watch the inner box, not the title. SplitText can lock title
				// width, so observing the title would miss an Elementor expand.
				if (detachResize) {
					detachResize();
				}
				detachResize = attachResize();

				if (!activeTween) {
					showLrFinal(root, title, inner, index, eyebrow);
					return;
				}

				markReady();
				setupScroll();
			} catch (e) {
				showLrFinal(root, title, inner, index, eyebrow);
				return;
			}

			setTimeout(function () {
				if (destroyed) {
					return;
				}
				if (!root.classList.contains('is-ready')) {
					showLrFinal(root, title, inner, index, eyebrow);
					return;
				}
				if (!hasPlayed && !root.classList.contains('is-playing') && !root.classList.contains('is-complete')) {
					var rect = root.getBoundingClientRect();
					var vh = window.innerHeight || 0;
					if (rect.top < vh && rect.bottom > 0) {
						playNow();
					}
				}
			}, 1200);
		}

		root._bwLrCleanup = function () {
			destroyed = true;

			if (detachResize) {
				detachResize();
				detachResize = null;
			}

			if (scrollTrigger && typeof scrollTrigger.kill === 'function') {
				scrollTrigger.kill();
			}
			scrollTrigger = null;

			revertSplit();
			title.style.visibility = '';
			root.classList.remove('is-ready', 'is-complete', 'is-playing');
		};

		whenFontsReady(function () {
			if (!destroyed) {
				startEngine();
			}
		});
	}

	function refreshScrollTrigger(delayMs) {
		if (typeof ScrollTrigger === 'undefined' || !ScrollTrigger.refresh) {
			return;
		}
		if (typeof ScrollTrigger.config === 'function') {
			ScrollTrigger.config({ limitCallbacks: true });
		}
		if (delayMs) {
			window.setTimeout(function () {
				if (typeof ScrollTrigger !== 'undefined' && ScrollTrigger.refresh) {
					ScrollTrigger.refresh();
				}
			}, delayMs);
			return;
		}
		ScrollTrigger.refresh();
	}

	window.addEventListener('load', function () {
		refreshScrollTrigger(120);
	});

	function initInScope(scope) {
		var rootEl = document;
		if (scope && scope.nodeType === 1) {
			rootEl = scope;
		} else if (scope && scope.jquery && scope[0] && scope[0].nodeType === 1) {
			rootEl = scope[0];
		}
		var roots = rootEl.querySelectorAll('[data-bw-animated-text="1"]');
		Array.prototype.forEach.call(roots, function (root) {
			try {
				cleanupInstance(root);
				delete root.dataset.bwAtInit;
				delete root.dataset.bwAtPending;
				registerPlugins();
				initInstance(root);
			} catch (e) {
				if (root && root.classList) {
					root.classList.add('is-ready', 'is-complete');
				}
			}
		});
		var lrRoots = rootEl.querySelectorAll('[data-bw-line-reveal="1"]');
		Array.prototype.forEach.call(lrRoots, function (root) {
			try {
				cleanupLineReveal(root);
				delete root.dataset.bwLrInit;
				delete root.dataset.bwLrPending;
				registerPlugins();
				initLineReveal(root);
			} catch (e) {
				if (root && root.classList) {
					root.classList.add('is-ready', 'is-complete');
				}
			}
		});
		refreshScrollTrigger(150);
	}

	if (window.jQuery) {
		jQuery(window).on('elementor/frontend/init', function () {
			if (window.elementorFrontend && elementorFrontend.hooks) {
				elementorFrontend.hooks.addAction('frontend/element_ready/b_scroll_heat.default', function ($scope) {
					initInScope($scope);
				});
			}
		});
		jQuery(function () {
			initInScope(document);
		});
	} else if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			initInScope(document);
		});
	} else {
		initInScope(document);
	}
})();