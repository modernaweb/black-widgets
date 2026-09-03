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
		if (window.mwsEw && typeof mwsEw.ensureScrollTriggerConfig === 'function') {
			mwsEw.ensureScrollTriggerConfig();
		} else if (typeof ScrollTrigger.config === 'function') {
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
		if (window.mwsEw && typeof mwsEw.readMotionNumber === 'function') {
			return mwsEw.readMotionNumber(root, attr, cssProp, fallback);
		}
		// Prefer CSS vars - Elementor selector controls update them without remount.
		var fromCss = readCssNumber(root, cssProp, NaN);
		if (!isNaN(fromCss)) {
			return fromCss;
		}
		var raw = root.getAttribute(attr);
		if (raw !== null && raw !== '') {
			return parseNumber(raw, fallback);
		}
		return fallback;
	}

	function isElementorEditMode() {
		if (window.mwsEw && typeof mwsEw.isElementorEditMode === 'function') {
			return mwsEw.isElementorEditMode();
		}
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
		if (window.mwsEw && typeof mwsEw.createEnterTrigger === 'function') {
			return mwsEw.createEnterTrigger(vars);
		}
		try {
			return ScrollTrigger.create(vars);
		} catch (e) {
			return null;
		}
	}

	function readMotionOpts(root, defaults, triggerMode) {
		return {
			duration: readDataNumber(root, 'data-duration', '--mws-ew-at-duration', defaults.duration),
			stagger: readDataNumber(root, 'data-stagger', '--mws-ew-at-stagger', defaults.stagger),
			delay: readDataNumber(root, 'data-delay', '--mws-ew-at-delay', triggerMode === 'load' ? 0.15 : 0),
			scrub: readDataNumber(root, 'data-scrub', '--mws-ew-at-scrub', 1)
		};
	}

	function prefersReducedMotion() {
		if (window.mwsEw && typeof mwsEw.prefersReducedMotion === 'function') {
			return mwsEw.prefersReducedMotion();
		}
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
		if (window.mwsEw && typeof mwsEw.canUseGsap === 'function') {
			return mwsEw.canUseGsap() && registerPlugins();
		}
		return typeof gsap !== 'undefined' && registerPlugins();
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

	function createSplit(title, splitType) {
		var config;

		if (splitType === 'lines') {
			config = {
				type: 'lines',
				linesClass: 'mws-ew-at__line',
				mask: 'lines'
			};
		} else if (splitType === 'words') {
			config = {
				type: 'words',
				wordsClass: 'mws-ew-at__word'
			};
		} else {
			config = {
				type: 'words,chars',
				wordsClass: 'mws-ew-at__word-holder',
				charsClass: 'mws-ew-at__char'
			};
		}

		try {
			if (typeof SplitText.create === 'function') {
				return SplitText.create(title, config);
			}
			return new SplitText(title, config);
		} catch (e) {
			return null;
		}
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
		if (!root.classList.contains('mws-ew-at--gradient')) {
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
			el._mwsGradOffset = offsets[i];
		}

		for (i = 0; i < targets.length; i++) {
			el = targets[i];
			if (!el) {
				continue;
			}
			el.style.backgroundImage = 'var(--mws-ew-at-grad-image)';
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

		if (animType === 'mask' && root && root.classList.contains('mws-ew-at--gradient')) {
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
								sweep.pos - (el._mwsGradOffset || 0) + 'px 0';
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

		if (typeof root._mwsEwAtCancelGsap === 'function') {
			root._mwsEwAtCancelGsap();
			delete root._mwsEwAtCancelGsap;
		}

		if (typeof root._mwsEwAtCleanup === 'function') {
			root._mwsEwAtCleanup();
		}

		delete root._mwsEwAtCleanup;
		delete root.dataset.mwsEwAtInit;
		delete root.dataset.mwsEwAtPending;
		root.removeAttribute('data-mws-ew-at-tries');
		root.classList.remove('is-ready', 'is-complete', 'is-playing');
	}

	function initInstance(root) {
		if (!root || root.dataset.mwsEwAtInit === '1') {
			return;
		}

		var title = root.querySelector('.mws-ew-at__title');
		if (!title) {
			return;
		}

		root.dataset.mwsEwAtInit = '1';

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
			if (typeof gsap !== 'undefined') {
				gsap.set(title, { clearProps: 'transform,opacity,filter,backgroundPosition' });
			}
		}

		function ensureSplit() {
			if (!splitInstance) {
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
				if (window.mwsEw && typeof mwsEw.whenGsapReady === 'function') {
					if (root.dataset.mwsEwAtPending === '1') {
						return;
					}
					root.dataset.mwsEwAtPending = '1';
					root._mwsEwAtCancelGsap = mwsEw.whenGsapReady(
						function () {
							delete root.dataset.mwsEwAtPending;
							delete root._mwsEwAtCancelGsap;
							if (!destroyed) {
								startEngine();
							}
						},
						{
							plugins: ['SplitText', 'ScrollTrigger'],
							onReduced: function () {
								delete root.dataset.mwsEwAtPending;
								delete root._mwsEwAtCancelGsap;
								showFinalState(root, title);
							},
							onGiveUp: function () {
								delete root.dataset.mwsEwAtPending;
								delete root._mwsEwAtCancelGsap;
								showFinalState(root, title);
							}
						}
					);
					return;
				}
				var tries = parseInt(root.getAttribute('data-mws-ew-at-tries') || '0', 10);
				if (tries < 25) {
					root.setAttribute('data-mws-ew-at-tries', String(tries + 1));
					setTimeout(function () {
						if (!destroyed) {
							startEngine();
						}
					}, 160);
					return;
				}
				showFinalState(root, title);
				return;
			}

			root.removeAttribute('data-mws-ew-at-tries');
			delete root.dataset.mwsEwAtPending;

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

		root._mwsEwAtCleanup = function () {
			destroyed = true;
			isBusy = false;

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
			duration: readDataNumber(root, 'data-duration', '--mws-ew-lr-duration', 1),
			stagger: readDataNumber(root, 'data-stagger', '--mws-ew-lr-stagger', 0.09)
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
				// overflow:clip does not create a BFC, so the first line would not shrink beside the float.
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
			mask.className = 'mws-ew-at__line-mask mws-ew-lr__line-mask';
			mask.setAttribute('aria-hidden', 'true');
			mask.style.display = 'block';
			mask.style.overflow = 'hidden';
			line = document.createElement('span');
			line.className = 'mws-ew-at__line mws-ew-lr__line';
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
		if (!eyebrow || !split || !split.lines || !split.lines[0]) {
			return;
		}
		if (window.matchMedia && window.matchMedia('(max-width: 767px)').matches) {
			eyebrow.style.height = '';
			return;
		}
		var h = split.lines[0].getBoundingClientRect().height;
		if (h > 0) {
			eyebrow.style.height = h + 'px';
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

		if (typeof root._mwsEwLrCancelGsap === 'function') {
			root._mwsEwLrCancelGsap();
			delete root._mwsEwLrCancelGsap;
		}

		if (typeof root._mwsEwLrCleanup === 'function') {
			root._mwsEwLrCleanup();
		}

		delete root._mwsEwLrCleanup;
		delete root.dataset.mwsEwLrInit;
		delete root.dataset.mwsEwLrPending;
		root.removeAttribute('data-mws-ew-lr-tries');
		root.classList.remove('is-ready', 'is-complete', 'is-playing');
	}

	function initLineReveal(root) {
		if (!root || root.dataset.mwsEwLrInit === '1') {
			return;
		}

		var title = root.querySelector('.mws-ew-lr__title');
		if (!title) {
			return;
		}

		root.dataset.mwsEwLrInit = '1';

		var originalHTML = title.innerHTML;
		var inner = root.querySelector('.mws-ew-lr__inner');
		var index = root.querySelector('.mws-ew-lr__index');
		var eyebrow = root.querySelector('.mws-ew-lr__eyebrow');
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
			if (eyebrow) {
				eyebrow.style.height = '';
			}
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
				gsap.set(title, { clearProps: 'transform,opacity,visibility' });
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
			syncEyebrowHeight(eyebrow, self);
		}

		function onSplit(self) {
			finishSplit(self);
			splitInstance = self;
			activeTween = buildLrTimeline(root, inner, index, eyebrow, self);
			return activeTween;
		}

		function createModernSplit() {
			try {
				if (isRtlContext(title)) {
					return SplitText.create(title, {
						type: 'words',
						wordsClass: 'mws-ew-at__word',
						autoSplit: true,
						onSplit: onSplit
					});
				}
				return SplitText.create(title, {
					type: 'lines',
					linesClass: 'mws-ew-at__line mws-ew-lr__line',
					mask: 'lines',
					autoSplit: true,
					onSplit: onSplit
				});
			} catch (e) {
				return null;
			}
		}

		function createLegacySplit() {
			try {
				if (isRtlContext(title)) {
					return new SplitText(title, {
						type: 'words',
						wordsClass: 'mws-ew-at__word'
					});
				}
				return new SplitText(title, {
					type: 'lines',
					linesClass: 'mws-ew-at__line mws-ew-lr__line'
				});
			} catch (e) {
				return null;
			}
		}

		function rebuildLegacy(keepComplete) {
			var complete = keepComplete || root.classList.contains('is-complete') || (hasPlayed && !replay);

			revertSplit();
			splitInstance = createLegacySplit();
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

		function attachLegacyResize() {
			var lastWidth = title.getBoundingClientRect().width;
			var timer = 0;

			function check() {
				if (destroyed) {
					return;
				}
				var w = title.getBoundingClientRect().width;
				if (Math.abs(w - lastWidth) < 1) {
					return;
				}
				lastWidth = w;
				rebuildLegacy(false);
			}

			function onResize() {
				clearTimeout(timer);
				timer = setTimeout(check, 200);
			}

			window.addEventListener('resize', onResize);
			var ro = null;
			if (typeof ResizeObserver === 'function') {
				ro = new ResizeObserver(onResize);
				ro.observe(title);
			}

			return function () {
				window.removeEventListener('resize', onResize);
				clearTimeout(timer);
				if (ro) {
					ro.disconnect();
				}
			};
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
				if (window.mwsEw && typeof mwsEw.whenGsapReady === 'function') {
					if (root.dataset.mwsEwLrPending === '1') {
						return;
					}
					root.dataset.mwsEwLrPending = '1';
					root._mwsEwLrCancelGsap = mwsEw.whenGsapReady(
						function () {
							delete root.dataset.mwsEwLrPending;
							delete root._mwsEwLrCancelGsap;
							if (!destroyed) {
								startEngine();
							}
						},
						{
							plugins: ['SplitText', 'ScrollTrigger'],
							onReduced: function () {
								delete root.dataset.mwsEwLrPending;
								delete root._mwsEwLrCancelGsap;
								showLrFinal(root, title, inner, index, eyebrow);
							},
							onGiveUp: function () {
								delete root.dataset.mwsEwLrPending;
								delete root._mwsEwLrCancelGsap;
								showLrFinal(root, title, inner, index, eyebrow);
							}
						}
					);
					return;
				}
				var tries = parseInt(root.getAttribute('data-mws-ew-lr-tries') || '0', 10);
				if (tries < 25) {
					root.setAttribute('data-mws-ew-lr-tries', String(tries + 1));
					setTimeout(function () {
						if (!destroyed) {
							startEngine();
						}
					}, 160);
					return;
				}
				showLrFinal(root, title, inner, index, eyebrow);
				return;
			}

			root.removeAttribute('data-mws-ew-lr-tries');
			delete root.dataset.mwsEwLrPending;

			title.style.visibility = 'hidden';

			try {
				if (canAutoSplit()) {
					splitInstance = createModernSplit();
				}

				if (!splitInstance) {
					splitInstance = createLegacySplit();
					if (!splitInstance) {
						showLrFinal(root, title, inner, index, eyebrow);
						return;
					}
					finishSplit(splitInstance);
					activeTween = buildLrTimeline(root, inner, index, eyebrow, splitInstance);
					detachResize = attachLegacyResize();
				}

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

		root._mwsEwLrCleanup = function () {
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
		if (window.mwsEw && typeof mwsEw.refreshScrollTrigger === 'function') {
			mwsEw.refreshScrollTrigger(delayMs);
			return;
		}
		if (typeof ScrollTrigger !== 'undefined' && ScrollTrigger.refresh) {
			if (typeof ScrollTrigger.config === 'function') {
				ScrollTrigger.config({ limitCallbacks: true });
			}
			ScrollTrigger.refresh();
		}
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
		var roots = rootEl.querySelectorAll('[data-bw-animated-text="1"], [data-mws-ew-at]');
		Array.prototype.forEach.call(roots, function (root) {
			try {
				cleanupInstance(root);
				delete root.dataset.mwsEwAtInit;
				delete root.dataset.mwsEwAtPending;
				registerPlugins();
				initInstance(root);
			} catch (e) {
				if (root && root.classList) {
					root.classList.add('is-ready', 'is-complete');
				}
			}
		});
		var lrRoots = rootEl.querySelectorAll('[data-bw-line-reveal="1"], [data-mws-ew-lr]');
		Array.prototype.forEach.call(lrRoots, function (root) {
			try {
				cleanupLineReveal(root);
				delete root.dataset.mwsEwLrInit;
				delete root.dataset.mwsEwLrPending;
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
