/**
 * Black Button effect types: Marquee, Vertical Swap, Arrow Slide, Perspective Flip.
 * Requires GSAP core; Perspective Flip also needs SplitText.
 */
(function ($) {
	'use strict';

	function canAnimate() {
		return typeof gsap !== 'undefined';
	}

	function reduceMotion() {
		try {
			return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
		} catch (e) {
			return false;
		}
	}

	function isRtlEl(el) {
		return getComputedStyle(el).direction === 'rtl';
	}

	function isEditMode() {
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

	function isTouchPointer(type) {
		return type === 'touch' || type === 'pen';
	}

	function bindHoverPair(btn, onEnter, onLeave, cleanups, holdMs) {
		holdMs = typeof holdMs === 'number' ? holdMs : 720;
		var leaveTimer = null;

		function clearLeave() {
			if (leaveTimer) {
				clearTimeout(leaveTimer);
				leaveTimer = null;
			}
		}

		function onMouseEnter(e) {
			if (btn._bwAbTouchLock) {
				return;
			}
			clearLeave();
			onEnter(e);
		}

		function onMouseLeave(e) {
			if (btn._bwAbTouchLock || btn._bwAbSkipLeave) {
				return;
			}
			clearLeave();
			onLeave(e);
		}

		function onPointerDown(e) {
			if (!isTouchPointer(e.pointerType)) {
				return;
			}
			btn._bwAbTouchLock = true;
			clearLeave();
			onEnter(e);
			leaveTimer = setTimeout(function () {
				btn._bwAbTouchLock = false;
				onLeave(e);
			}, holdMs);
		}

		btn.addEventListener('mouseenter', onMouseEnter);
		btn.addEventListener('mouseleave', onMouseLeave);
		btn.addEventListener('pointerdown', onPointerDown);
		cleanups.push(function () {
			clearLeave();
			delete btn._bwAbTouchLock;
			delete btn._bwAbSkipLeave;
			btn.removeEventListener('mouseenter', onMouseEnter);
			btn.removeEventListener('mouseleave', onMouseLeave);
			btn.removeEventListener('pointerdown', onPointerDown);
		});
	}

	function bindHoverEnter(btn, onEnter, cleanups) {
		function onMouseEnter(e) {
			if (btn._bwAbTouchLock) {
				return;
			}
			onEnter(e);
		}

		function onPointerDown(e) {
			if (!isTouchPointer(e.pointerType)) {
				return;
			}
			btn._bwAbTouchLock = true;
			onEnter(e);
			setTimeout(function () {
				btn._bwAbTouchLock = false;
			}, 720);
		}

		btn.addEventListener('mouseenter', onMouseEnter);
		btn.addEventListener('pointerdown', onPointerDown);
		cleanups.push(function () {
			delete btn._bwAbTouchLock;
			btn.removeEventListener('mouseenter', onMouseEnter);
			btn.removeEventListener('pointerdown', onPointerDown);
		});
	}

	function bindTouchNavGate(btn, cleanups, holdMs) {
		if (!btn || String(btn.tagName).toLowerCase() !== 'a') {
			return;
		}
		if (reduceMotion()) {
			return;
		}

		holdMs = typeof holdMs === 'number' ? holdMs : 520;
		var timer = null;
		var pending = false;

		function clearTimer() {
			if (timer) {
				clearTimeout(timer);
				timer = null;
			}
		}

		function navigateTo(href, target) {
			if (target && target !== '_self') {
				window.open(href, target);
				pending = false;
				btn._bwAbSkipLeave = false;
				btn._bwAbTouchLock = false;
				btn.classList.remove('is-nav-pending');
				return;
			}
			window.location.assign(href);
		}

		function onClick(e) {
			if (!isTouchPointer(btn._bwAbLastPointer) && !btn._bwAbTouchLock) {
				return;
			}
			if (isEditMode()) {
				e.preventDefault();
				return;
			}
			var href = btn.getAttribute('href');
			if (!href || href === '#' || href.indexOf('javascript:') === 0) {
				return;
			}
			e.preventDefault();
			e.stopPropagation();
			if (pending) {
				return;
			}
			pending = true;
			btn._bwAbSkipLeave = true;
			btn.classList.add('is-nav-pending');
			clearTimer();
			timer = setTimeout(function () {
				navigateTo(href, btn.getAttribute('target'));
			}, holdMs);
		}

		function onPointerDown(e) {
			btn._bwAbLastPointer = e.pointerType || 'mouse';
			if (isTouchPointer(e.pointerType)) {
				btn._bwAbTouchLock = true;
			}
		}

		btn.addEventListener('pointerdown', onPointerDown, true);
		btn.addEventListener('click', onClick, true);
		cleanups.push(function () {
			clearTimer();
			pending = false;
			btn._bwAbSkipLeave = false;
			btn._bwAbTouchLock = false;
			btn.classList.remove('is-nav-pending');
			btn.removeEventListener('pointerdown', onPointerDown, true);
			btn.removeEventListener('click', onClick, true);
		});
	}

	var TOUCH_NAV_HOLD = {
		arrow: 280,
		split: 720,
		marquee: 480,
		swap: 480
	};

	function initArrow(btn, cleanups) {
		var arrows = btn.querySelectorAll('.bw-ab__arrow');
		var txt = btn.querySelector('.bw-ab__txt');
		if (arrows.length < 2 || !txt) {
			return;
		}
		var direction = isRtlEl(btn) ? -1 : 1;
		gsap.set(arrows[0], { xPercent: 0, opacity: 1 });
		gsap.set(arrows[1], { xPercent: -150 * direction, opacity: 0 });

		var tl = gsap.timeline({ paused: true });
		tl.to(arrows[0], { xPercent: 150 * direction, opacity: 0, duration: 0.22 }, 0)
			.to(arrows[1], { xPercent: 0, opacity: 1, duration: 0.22 }, 0)
			.to(txt, { x: -4 * direction, duration: 0.22 }, 0);

		function onEnter() {
			if (reduceMotion()) {
				return;
			}
			tl.play();
		}
		function onLeave() {
			tl.reverse();
		}

		bindHoverPair(btn, onEnter, onLeave, cleanups, TOUCH_NAV_HOLD.arrow);

		cleanups.push(function () {
			tl.kill();
			gsap.set([arrows[0], arrows[1], txt], { clearProps: 'transform,opacity,x,xPercent' });
		});
	}

	function initSplit(btn, cleanups) {
		var txt = btn.querySelector('.bw-ab__txt');
		if (!txt || typeof SplitText === 'undefined') {
			return;
		}
		var split;
		try {
			split = typeof SplitText.create === 'function'
				? SplitText.create(txt, { type: 'chars', charsClass: 'bw-ab__char' })
				: new SplitText(txt, { type: 'chars', charsClass: 'bw-ab__char' });
		} catch (e) {
			return;
		}
		if (!split || !split.chars || !split.chars.length) {
			return;
		}

		gsap.registerPlugin(SplitText);
		gsap.set(split.chars, { transformOrigin: '50% 50%', force3D: true });

		var animating = false;
		var tl = null;
		var staggerFrom = isRtlEl(btn) ? 'end' : 'start';

		function onEnter() {
			if (animating || reduceMotion()) {
				return;
			}
			animating = true;
			if (tl) {
				tl.kill();
			}
			tl = gsap.timeline({
				onComplete: function () {
					animating = false;
				}
			});
			tl.fromTo(
				split.chars,
				{ rotateX: 0, y: 0, opacity: 1 },
				{
					rotateX: -90,
					y: -4,
					opacity: 0.35,
					duration: 0.22,
					ease: 'power2.in',
					stagger: { each: 0.02, from: staggerFrom }
				}
			).to(split.chars, {
				rotateX: 0,
				y: 0,
				opacity: 1,
				duration: 0.38,
				ease: 'back.out(1.6)',
				stagger: { each: 0.02, from: staggerFrom }
			});
		}

		bindHoverEnter(btn, onEnter, cleanups);
		cleanups.push(function () {
			if (tl) {
				tl.kill();
			}
			gsap.killTweensOf(split.chars);
			if (split.revert) {
				split.revert();
			}
		});
	}

	function initMarquee(btn, cleanups) {
		var mask = btn.querySelector('.bw-ab__marquee-mask');
		var track = btn.querySelector('.bw-ab__marquee-track');
		var items = track ? track.querySelectorAll('.bw-ab__marquee-item') : [];
		if (!mask || !track || items.length < 2) {
			return;
		}

		var tween = null;
		var resetTween = null;
		var distance = 0;
		var loopDuration = 1.1;

		function measure() {
			var first = items[0];
			var w = Math.ceil(first.getBoundingClientRect().width);
			var styles = window.getComputedStyle(track);
			var gap = parseFloat(styles.columnGap || styles.gap) || 0;
			distance = Math.max(1, w + gap);
			loopDuration = Math.max(1.1, distance / 70);
			mask.style.width = w + 'px';
			return distance;
		}

		function killReset() {
			if (resetTween) {
				resetTween.kill();
				resetTween = null;
			}
		}

		function buildTween(paused) {
			if (tween) {
				tween.kill();
				tween = null;
			}
			measure();
			gsap.set(track, { x: 0 });
			var dir = isRtlEl(btn) ? 1 : -1;
			tween = gsap.to(track, {
				x: dir * distance,
				duration: loopDuration,
				ease: 'none',
				repeat: -1,
				paused: !!paused
			});
		}

		buildTween(true);

		var resizeObs = null;
		if (typeof ResizeObserver !== 'undefined') {
			resizeObs = new ResizeObserver(function () {
				var wasPlaying = tween && tween.isActive();
				killReset();
				buildTween(!wasPlaying || reduceMotion());
				if (wasPlaying && !reduceMotion() && tween) {
					tween.play();
				}
			});
			resizeObs.observe(items[0]);
		}

		function onEnter() {
			if (reduceMotion()) {
				return;
			}
			killReset();
			buildTween(false);
		}

		function onLeave() {
			if (!tween) {
				return;
			}
			var curX = parseFloat(gsap.getProperty(track, 'x')) || 0;
			tween.pause();
			killReset();
			var dur = Math.min(0.45, Math.abs(curX) / Math.max(distance, 1) * loopDuration);
			if (dur < 0.05) {
				gsap.set(track, { x: 0 });
				return;
			}
			resetTween = gsap.to(track, {
				x: 0,
				duration: dur,
				ease: 'power2.out',
				onComplete: function () {
					gsap.set(track, { x: 0 });
					resetTween = null;
				}
			});
		}

		bindHoverPair(btn, onEnter, onLeave, cleanups, TOUCH_NAV_HOLD.marquee);
		cleanups.push(function () {
			killReset();
			if (resizeObs) {
				resizeObs.disconnect();
			}
			if (tween) {
				tween.kill();
			}
			gsap.killTweensOf(track);
			gsap.set(track, { clearProps: 'transform,x' });
			mask.style.width = '';
		});
	}

	function initSwap(btn, cleanups) {
		var top = btn.querySelector('.bw-ab__swap-top');
		var bottom = btn.querySelector('.bw-ab__swap-bottom');
		if (!top || !bottom) {
			return;
		}

		gsap.set(top, { yPercent: 0 });
		gsap.set(bottom, { yPercent: 100 });

		bindHoverPair(
			btn,
			function () {
				if (reduceMotion()) {
					return;
				}
				gsap.to(top, { yPercent: -100, duration: 0.45, ease: 'power3.out' });
				gsap.to(bottom, { yPercent: 0, duration: 0.45, ease: 'power3.out' });
			},
			function () {
				gsap.to(top, { yPercent: 0, duration: 0.35, ease: 'power2.out' });
				gsap.to(bottom, { yPercent: 100, duration: 0.35, ease: 'power2.out' });
			},
			cleanups,
			TOUCH_NAV_HOLD.swap
		);

		cleanups.push(function () {
			gsap.killTweensOf([top, bottom]);
			gsap.set([top, bottom], { clearProps: 'transform,yPercent' });
		});
	}

	function bindEffects(root, btn) {
		var effect = root.getAttribute('data-effect') || '';
		var cleanups = [];

		switch (effect) {
			case 'arrow':
				initArrow(btn, cleanups);
				break;
			case 'split':
				initSplit(btn, cleanups);
				break;
			case 'marquee':
				initMarquee(btn, cleanups);
				break;
			case 'swap':
				initSwap(btn, cleanups);
				break;
			default:
				break;
		}

		bindTouchNavGate(btn, cleanups, TOUCH_NAV_HOLD[effect] || 520);

		root._bwAbCleanup = function () {
			while (cleanups.length) {
				var fn = cleanups.pop();
				try {
					fn();
				} catch (e) { /* ignore */ }
			}
		};
	}

	function cleanupInstance(root) {
		if (!root) {
			return;
		}
		if (root._bwAbCleanup) {
			root._bwAbCleanup();
			root._bwAbCleanup = null;
		}
		delete root.dataset.bwAbInit;
		delete root.dataset.bwAbPending;
		root.removeAttribute('data-bw-ab-tries');
	}

	function initInstance(root) {
		if (!root || root.dataset.bwAbInit === '1' || root.dataset.bwAbPending === '1') {
			return;
		}

		var btn = root.querySelector('.bw-ab__btn');
		if (!btn) {
			return;
		}

		function markDone() {
			delete root.dataset.bwAbPending;
			root.dataset.bwAbInit = '1';
			root.removeAttribute('data-bw-ab-tries');
		}

		if (reduceMotion()) {
			markDone();
			return;
		}

		var effect = root.getAttribute('data-effect') || '';
		if (effect === 'split' && typeof SplitText === 'undefined') {
			var splitTries = parseInt(root.getAttribute('data-bw-ab-tries') || '0', 10);
			if (splitTries < 25 && canAnimate()) {
				root.setAttribute('data-bw-ab-tries', String(splitTries + 1));
				root.dataset.bwAbPending = '1';
				setTimeout(function () {
					delete root.dataset.bwAbPending;
					initInstance(root);
				}, 160);
				return;
			}
			markDone();
			return;
		}

		if (!canAnimate()) {
			var tries = parseInt(root.getAttribute('data-bw-ab-tries') || '0', 10);
			if (tries < 25) {
				root.setAttribute('data-bw-ab-tries', String(tries + 1));
				root.dataset.bwAbPending = '1';
				setTimeout(function () {
					delete root.dataset.bwAbPending;
					initInstance(root);
				}, 160);
				return;
			}
			markDone();
			return;
		}

		markDone();
		bindEffects(root, btn);
	}

	function initInScope($scope) {
		var $roots = ($scope && $scope.length ? $scope : $(document)).find('[data-bw-ab]');
		$roots.each(function () {
			cleanupInstance(this);
			initInstance(this);
		});
	}

	$(window).on('elementor/frontend/init', function () {
		if (window.elementorFrontend && elementorFrontend.hooks) {
			elementorFrontend.hooks.addAction('frontend/element_ready/b_button.default', function ($scope) {
				initInScope($scope);
			});
		}
	});

	$(function () {
		initInScope($(document));
	});
})(jQuery);
