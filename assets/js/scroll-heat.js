/**
 * Black Scroll Heat (Type 1)
 *
 * Scrubbed word-by-word color reveal via GSAP SplitText + ScrollTrigger.
 * Loads GSAP from Black Widgets CDN settings (core + ScrollTrigger + SplitText).
 */
(function () {
	'use strict';

	var scopeMap = typeof WeakMap !== 'undefined' ? new WeakMap() : null;
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
		return parseNumber(window.getComputedStyle(root).getPropertyValue(prop), fallback);
	}

	function readCssColor(root, prop, fallback) {
		if (!root || !window.getComputedStyle) {
			return fallback;
		}
		var raw = window.getComputedStyle(root).getPropertyValue(prop);
		raw = raw ? String(raw).trim() : '';
		return raw || fallback;
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

	function createSplit(textEl) {
		var config = {
			type: 'words',
			wordsClass: 'mws-ew-hw__word'
		};
		try {
			if (typeof SplitText.create === 'function') {
				return SplitText.create(textEl, config);
			}
			return new SplitText(textEl, config);
		} catch (e) {
			return null;
		}
	}

	function refreshScrollTrigger(delayMs) {
		if (window.mwsEw && typeof mwsEw.refreshScrollTrigger === 'function') {
			mwsEw.refreshScrollTrigger(delayMs);
			return;
		}
		if (typeof ScrollTrigger !== 'undefined' && ScrollTrigger.refresh) {
			ScrollTrigger.refresh();
		}
	}

	function whenFontsReady(cb) {
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(cb).catch(cb);
			return;
		}
		cb();
	}

	function applyReducedState(root) {
		if (!root) {
			return;
		}
		root.classList.add('is-reduced');
		root.classList.remove('is-ready');
		var words = root.querySelectorAll('.mws-ew-hw__word');
		var lit = readCssColor(root, '--mws-ew-hw-lit', '#16171B');
		Array.prototype.forEach.call(words, function (word) {
			word.style.color = lit;
		});
		if (!words.length) {
			var textEl = root.querySelector('.mws-ew-hw__text');
			if (textEl) {
				textEl.style.color = lit;
			}
		}
	}

	function cleanupInstance(root) {
		if (!root) {
			return;
		}
		if (typeof root._mwsEwHwCancelGsap === 'function') {
			root._mwsEwHwCancelGsap();
			delete root._mwsEwHwCancelGsap;
		}
		root._mwsEwHwGen = (root._mwsEwHwGen || 0) + 1;

		if (scopeMap && scopeMap.has(root)) {
			var inst = scopeMap.get(root);
			if (inst.tl && typeof inst.tl.kill === 'function') {
				inst.tl.kill();
			}
			if (inst.st && typeof inst.st.kill === 'function') {
				inst.st.kill();
			}
			if (inst.split && typeof inst.split.revert === 'function') {
				inst.split.revert();
			}
			if (typeof inst.unsubResize === 'function') {
				inst.unsubResize();
			}
			scopeMap.delete(root);
		}

		root.classList.remove('is-ready');
		root.classList.remove('is-reduced');
		delete root.dataset.mwsEwHwInit;
		delete root.dataset.mwsEwHwPending;
		root.removeAttribute('data-mws-ew-hw-tries');
	}

	function bindScrollHeat(root) {
		cleanupInstance(root);
		root.dataset.mwsEwHwInit = '1';
		var gen = root._mwsEwHwGen || 0;

		var textEl = root.querySelector('.mws-ew-hw__text');
		if (!textEl) {
			return;
		}

		if (prefersReducedMotion() || !canAnimate()) {
			applyReducedState(root);
			return;
		}

		whenFontsReady(function () {
			if (!root.isConnected || (root._mwsEwHwGen || 0) !== gen) {
				return;
			}

			var start = root.getAttribute('data-start') || 'top 78%';
			var end = root.getAttribute('data-end') || 'bottom 32%';
			var scrubAttr = root.getAttribute('data-scrub');
			var staggerAttr = root.getAttribute('data-stagger');
			var scrub = scrubAttr !== null && scrubAttr !== ''
				? parseNumber(scrubAttr, 0.35)
				: readCssNumber(root, '--mws-ew-hw-scrub', 0.35);
			var stagger = staggerAttr !== null && staggerAttr !== ''
				? parseNumber(staggerAttr, 0.06)
				: readCssNumber(root, '--mws-ew-hw-stagger', 0.06);
			var lit = readCssColor(root, '--mws-ew-hw-lit', '#16171B');
			var dim = readCssColor(root, '--mws-ew-hw-dim', '#D7D8D4');

			var split;
			try {
				split = createSplit(textEl);
			} catch (e) {
				applyReducedState(root);
				return;
			}

			var words = (split && split.words) || [];
			if (!words.length) {
				if (split && typeof split.revert === 'function') {
					split.revert();
				}
				applyReducedState(root);
				return;
			}

			gsap.set(words, { color: dim });
			root.classList.add('is-ready');

			var tl;
			try {
				tl = gsap.timeline({
					scrollTrigger: {
						trigger: root,
						start: start,
						end: end,
						scrub: scrub <= 0 ? true : scrub
					}
				});

				tl.to(words, {
					color: lit,
					ease: 'none',
					stagger: Math.max(0.01, stagger)
				});
			} catch (e) {
				if (split && typeof split.revert === 'function') {
					try {
						split.revert();
					} catch (revertErr) { /* ignore */ }
				}
				applyReducedState(root);
				return;
			}

			var st = tl.scrollTrigger || null;

			var resizeT = null;
			var onResize = function () {
				clearTimeout(resizeT);
				resizeT = setTimeout(function () {
					refreshScrollTrigger(0);
				}, 160);
			};

			var unsubResize = null;
			if (window.mwsEw && typeof mwsEw.onResize === 'function') {
				unsubResize = mwsEw.onResize(onResize);
			} else {
				window.addEventListener('resize', onResize);
				unsubResize = function () {
					window.removeEventListener('resize', onResize);
				};
			}

			if (scopeMap) {
				scopeMap.set(root, {
					tl: tl,
					st: st,
					split: split,
					unsubResize: unsubResize
				});
			}

			refreshScrollTrigger(150);
		});
	}

	function initInstance(root) {
		if (!root || root.dataset.mwsEwHwInit === '1' || root.dataset.mwsEwHwPending === '1') {
			return;
		}

		if (prefersReducedMotion()) {
			applyReducedState(root);
			root.dataset.mwsEwHwInit = '1';
			return;
		}

		if (window.mwsEw && typeof mwsEw.whenGsapReady === 'function') {
			root.dataset.mwsEwHwPending = '1';
			root._mwsEwHwCancelGsap = mwsEw.whenGsapReady(
				function () {
					delete root.dataset.mwsEwHwPending;
					delete root._mwsEwHwCancelGsap;
					if (!root.isConnected || root.dataset.mwsEwHwInit === '1') {
						return;
					}
					if (!canAnimate()) {
						applyReducedState(root);
						root.dataset.mwsEwHwInit = '1';
						return;
					}
					root.removeAttribute('data-mws-ew-hw-tries');
					bindScrollHeat(root);
				},
				{
					plugins: ['ScrollTrigger', 'SplitText'],
					onReduced: function () {
						delete root.dataset.mwsEwHwPending;
						delete root._mwsEwHwCancelGsap;
						applyReducedState(root);
						root.dataset.mwsEwHwInit = '1';
						root.removeAttribute('data-mws-ew-hw-tries');
					},
					onGiveUp: function () {
						delete root.dataset.mwsEwHwPending;
						delete root._mwsEwHwCancelGsap;
						applyReducedState(root);
						root.dataset.mwsEwHwInit = '1';
						root.removeAttribute('data-mws-ew-hw-tries');
					}
				}
			);
			return;
		}

		if (!canAnimate()) {
			var tries = parseInt(root.getAttribute('data-mws-ew-hw-tries') || '0', 10);
			if (tries < 25) {
				root.setAttribute('data-mws-ew-hw-tries', String(tries + 1));
				setTimeout(function () {
					initInstance(root);
				}, 160);
				return;
			}
			applyReducedState(root);
			root.dataset.mwsEwHwInit = '1';
			return;
		}

		root.removeAttribute('data-mws-ew-hw-tries');
		bindScrollHeat(root);
	}

	function initInScope(scope) {
		var rootEl = document;
		if (scope && scope.nodeType === 1) {
			rootEl = scope;
		} else if (scope && scope.jquery && scope[0] && scope[0].nodeType === 1) {
			rootEl = scope[0];
		}
		var roots = rootEl.querySelectorAll('[data-bw-scroll-heat="1"], [data-mws-ew-hw="1"]');
		Array.prototype.forEach.call(roots, function (root) {
			cleanupInstance(root);
			delete root.dataset.mwsEwHwInit;
			delete root.dataset.mwsEwHwPending;
			registerPlugins();
			initInstance(root);
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

	registerPlugins();
})();
