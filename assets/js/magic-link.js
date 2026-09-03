/**
 * Black Magic Link - SplitText hover types.
 * Requires GSAP + SplitText (CDN1 + CDN3).
 */
(function ($) {
	'use strict';

	function canAnimate() {
		return typeof gsap !== 'undefined' && typeof SplitText !== 'undefined';
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

	function createSplit(el, charsClass) {
		try {
			if (typeof SplitText.create === 'function') {
				return SplitText.create(el, { type: 'chars', charsClass: charsClass });
			}
			return new SplitText(el, { type: 'chars', charsClass: charsClass });
		} catch (e) {
			return null;
		}
	}

	function bindHoverPair(link, onEnter, onLeave, cleanups, holdMs) {
		holdMs = typeof holdMs === 'number' ? holdMs : 640;
		var leaveTimer = null;

		function clearLeave() {
			if (leaveTimer) {
				clearTimeout(leaveTimer);
				leaveTimer = null;
			}
		}

		function onMouseEnter(e) {
			if (link._bwMlTouchLock) {
				return;
			}
			clearLeave();
			onEnter(e);
		}

		function onMouseLeave(e) {
			if (link._bwMlTouchLock || link._bwMlSkipLeave) {
				return;
			}
			clearLeave();
			onLeave(e);
		}

		function onPointerDown(e) {
			if (!isTouchPointer(e.pointerType)) {
				return;
			}
			link._bwMlTouchLock = true;
			clearLeave();
			onEnter(e);
			leaveTimer = setTimeout(function () {
				link._bwMlTouchLock = false;
				onLeave(e);
			}, holdMs);
		}

		link.addEventListener('mouseenter', onMouseEnter);
		link.addEventListener('mouseleave', onMouseLeave);
		link.addEventListener('pointerdown', onPointerDown);
		cleanups.push(function () {
			clearLeave();
			delete link._bwMlTouchLock;
			delete link._bwMlSkipLeave;
			link.removeEventListener('mouseenter', onMouseEnter);
			link.removeEventListener('mouseleave', onMouseLeave);
			link.removeEventListener('pointerdown', onPointerDown);
		});
	}

	function bindTouchNavGate(link, cleanups, holdMs) {
		if (!link || String(link.tagName).toLowerCase() !== 'a') {
			return;
		}
		if (reduceMotion()) {
			return;
		}

		holdMs = typeof holdMs === 'number' ? holdMs : 480;
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
			} else {
				window.location.assign(href);
			}
			pending = false;
			link._bwMlSkipLeave = false;
			link._bwMlTouchLock = false;
			link.classList.remove('is-nav-pending');
		}

		function onClick(e) {
			if (!isTouchPointer(link._bwMlLastPointer) && !link._bwMlTouchLock) {
				return;
			}
			if (isEditMode()) {
				e.preventDefault();
				return;
			}
			var href = link.getAttribute('href');
			if (!href || href === '#' || href.indexOf('javascript:') === 0) {
				return;
			}
			e.preventDefault();
			e.stopPropagation();
			if (pending) {
				return;
			}
			pending = true;
			link._bwMlSkipLeave = true;
			link.classList.add('is-nav-pending');
			clearTimer();
			timer = setTimeout(function () {
				navigateTo(href, link.getAttribute('target'));
			}, holdMs);
		}

		function onPointerDown(e) {
			link._bwMlLastPointer = e.pointerType || 'mouse';
			if (isTouchPointer(e.pointerType)) {
				link._bwMlTouchLock = true;
			}
		}

		link.addEventListener('pointerdown', onPointerDown, true);
		link.addEventListener('click', onClick, true);
		cleanups.push(function () {
			clearTimer();
			pending = false;
			link._bwMlSkipLeave = false;
			link._bwMlTouchLock = false;
			link.classList.remove('is-nav-pending');
			link.removeEventListener('pointerdown', onPointerDown, true);
			link.removeEventListener('click', onClick, true);
		});
	}

	function initCharHover(link, cleanups, effect) {
		var txt = link.querySelector('.bw-ml__text');
		if (!txt) {
			return;
		}

		var split = createSplit(txt, 'bw-ml__char');
		if (!split || !split.chars || !split.chars.length) {
			return;
		}

		gsap.registerPlugin(SplitText);
		var chars = split.chars;
		var staggerFrom = isRtlEl(link) ? 'end' : 'start';
		var tl = null;

		gsap.set(chars, {
			display: 'inline-block',
			transformOrigin: '50% 50%',
			force3D: true,
		});

		function playEnter() {
			if (reduceMotion()) {
				return;
			}
			if (tl) {
				tl.kill();
			}
			tl = gsap.timeline();

			switch (effect) {
				case 'rise':
					tl.to(chars, {
						yPercent: -110,
						duration: 0.32,
						ease: 'power2.in',
						stagger: { each: 0.02, from: staggerFrom },
					}).fromTo(
						chars,
						{ yPercent: 110 },
						{
							yPercent: 0,
							duration: 0.45,
							ease: 'power3.out',
							stagger: { each: 0.02, from: staggerFrom },
						}
					);
					break;
				case 'fade':
					tl.to(chars, {
						opacity: 0,
						y: -10,
						duration: 0.22,
						ease: 'power2.in',
						stagger: { each: 0.02, from: staggerFrom },
					}).to(chars, {
						opacity: 1,
						y: 0,
						duration: 0.4,
						ease: 'power2.out',
						stagger: { each: 0.025, from: staggerFrom },
					});
					break;
				case 'wave':
					tl.to(chars, {
						y: -9,
						duration: 0.22,
						ease: 'power2.out',
						stagger: { each: 0.035, from: staggerFrom },
						yoyo: true,
						repeat: 1,
					});
					break;
				case 'flip':
					tl.fromTo(
						chars,
						{ rotateX: 0, opacity: 1 },
						{
							rotateX: -90,
							opacity: 0.35,
							duration: 0.28,
							ease: 'power2.in',
							stagger: { each: 0.02, from: staggerFrom },
						}
					).to(chars, {
						rotateX: 0,
						opacity: 1,
						duration: 0.42,
						ease: 'power3.out',
						stagger: { each: 0.02, from: staggerFrom },
					});
					break;
				case 'blur':
					tl.to(chars, {
						filter: 'blur(6px)',
						opacity: 0.35,
						duration: 0.22,
						ease: 'power1.in',
						stagger: { each: 0.02, from: staggerFrom },
					}).to(chars, {
						filter: 'blur(0px)',
						opacity: 1,
						duration: 0.4,
						ease: 'power2.out',
						stagger: { each: 0.025, from: staggerFrom },
					});
					break;
				case 'elastic':
					tl.to(chars, {
						scale: 0.55,
						y: 8,
						duration: 0.18,
						ease: 'power2.in',
						stagger: { each: 0.02, from: staggerFrom },
					}).to(chars, {
						scale: 1,
						y: 0,
						duration: 0.75,
						ease: 'elastic.out(1, 0.4)',
						stagger: { each: 0.03, from: staggerFrom },
					});
					break;
				default:
					break;
			}
		}

		function playLeave() {
			if (reduceMotion()) {
				return;
			}
			if (tl) {
				tl.kill();
			}
			tl = gsap.to(chars, {
				y: 0,
				yPercent: 0,
				rotateX: 0,
				scale: 1,
				opacity: 1,
				filter: 'blur(0px)',
				duration: 0.28,
				ease: 'power2.out',
				stagger: { each: 0.012, from: staggerFrom },
			});
		}

		bindHoverPair(link, playEnter, playLeave, cleanups, 640);

		cleanups.push(function () {
			if (tl) {
				tl.kill();
			}
			gsap.killTweensOf(chars);
			try {
				if (split && typeof split.revert === 'function') {
					split.revert();
				}
			} catch (e) {
				/* ignore */
			}
		});
	}

	function initSwap(link, cleanups) {
		var topLine = link.querySelector('.bw-ml__swap-top .bw-ml__text');
		var bottomLine = link.querySelector('.bw-ml__swap-bottom .bw-ml__text');
		var topWrap = link.querySelector('.bw-ml__swap-top');
		var bottomWrap = link.querySelector('.bw-ml__swap-bottom');
		if (!topLine || !bottomLine || !topWrap || !bottomWrap) {
			return;
		}

		var topSplit = createSplit(topLine, 'bw-ml__char');
		var bottomSplit = createSplit(bottomLine, 'bw-ml__char');
		if (!topSplit || !bottomSplit || !topSplit.chars || !bottomSplit.chars) {
			return;
		}

		gsap.registerPlugin(SplitText);
		var staggerFrom = isRtlEl(link) ? 'end' : 'start';

		gsap.set([topWrap, bottomWrap], { display: 'block', overflow: 'hidden' });
		gsap.set(topWrap, { yPercent: 0 });
		gsap.set(bottomWrap, { yPercent: 100, position: 'absolute', left: 0, top: 0, width: '100%' });
		gsap.set(topSplit.chars.concat(bottomSplit.chars), {
			display: 'inline-block',
			force3D: true,
		});

		bindHoverPair(
			link,
			function () {
				if (reduceMotion()) {
					return;
				}
				gsap.to(topWrap, { yPercent: -100, duration: 0.45, ease: 'power3.out' });
				gsap.to(bottomWrap, { yPercent: 0, duration: 0.45, ease: 'power3.out' });
				gsap.fromTo(
					bottomSplit.chars,
					{ yPercent: 40, opacity: 0.4 },
					{
						yPercent: 0,
						opacity: 1,
						duration: 0.4,
						ease: 'power2.out',
						stagger: { each: 0.02, from: staggerFrom },
					}
				);
			},
			function () {
				gsap.to(topWrap, { yPercent: 0, duration: 0.35, ease: 'power2.out' });
				gsap.to(bottomWrap, { yPercent: 100, duration: 0.35, ease: 'power2.out' });
			},
			cleanups,
			640
		);

		cleanups.push(function () {
			gsap.killTweensOf([topWrap, bottomWrap].concat(topSplit.chars, bottomSplit.chars));
			try {
				if (topSplit.revert) {
					topSplit.revert();
				}
				if (bottomSplit.revert) {
					bottomSplit.revert();
				}
			} catch (e) {
				/* ignore */
			}
		});
	}

	function bindEffects(root, link) {
		var effect = root.getAttribute('data-effect') || '';
		var cleanups = [];

		if (effect === 'swap') {
			initSwap(link, cleanups);
		} else {
			initCharHover(link, cleanups, effect);
		}

		bindTouchNavGate(link, cleanups, 480);

		root._bwMlCleanup = function () {
			while (cleanups.length) {
				var fn = cleanups.pop();
				try {
					fn();
				} catch (e) {
					/* ignore */
				}
			}
		};
	}

	function cleanupInstance(root) {
		if (!root) {
			return;
		}
		if (root._bwMlCleanup) {
			root._bwMlCleanup();
			root._bwMlCleanup = null;
		}
		delete root.dataset.bwMlInit;
		delete root.dataset.bwMlPending;
		root.removeAttribute('data-bw-ml-tries');
	}

	function initInstance(root) {
		if (!root || root.dataset.bwMlInit === '1' || root.dataset.bwMlPending === '1') {
			return;
		}

		var link = root.querySelector('.bw-ml__link');
		if (!link) {
			return;
		}

		function markDone() {
			delete root.dataset.bwMlPending;
			root.dataset.bwMlInit = '1';
			root.removeAttribute('data-bw-ml-tries');
		}

		if (reduceMotion()) {
			markDone();
			return;
		}

		if (!canAnimate()) {
			var tries = parseInt(root.getAttribute('data-bw-ml-tries') || '0', 10);
			if (tries < 25) {
				root.setAttribute('data-bw-ml-tries', String(tries + 1));
				root.dataset.bwMlPending = '1';
				setTimeout(function () {
					delete root.dataset.bwMlPending;
					initInstance(root);
				}, 160);
				return;
			}
			markDone();
			return;
		}

		markDone();
		bindEffects(root, link);
	}

	function initInScope($scope) {
		var $roots = ($scope && $scope.length ? $scope : $(document)).find('[data-bw-ml]');
		$roots.each(function () {
			cleanupInstance(this);
			initInstance(this);
		});
	}

	$(window).on('elementor/frontend/init', function () {
		if (window.elementorFrontend && elementorFrontend.hooks) {
			elementorFrontend.hooks.addAction('frontend/element_ready/b_magic.default', function ($scope) {
				initInScope($scope);
			});
		}
	});

	$(function () {
		initInScope($(document));
	});
})(jQuery);
