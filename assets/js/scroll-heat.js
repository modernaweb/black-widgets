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
		return parseNumber(window.getComputedStyle(root).getPropertyValue(prop), fallback);
	}

	function readCssNumberPref(root, prop, aliasProp, fallback) {
		var n = readCssNumber(root, prop, NaN);
		if (!isNaN(n)) {
			return n;
		}
		return readCssNumber(root, aliasProp, fallback);
	}

	function readCssColor(root, prop, fallback) {
		if (!root || !window.getComputedStyle) {
			return fallback;
		}
		var raw = window.getComputedStyle(root).getPropertyValue(prop);
		raw = raw ? String(raw).trim() : '';
		if (!raw || raw.indexOf('var(') !== -1) {
			return fallback;
		}
		return raw;
	}

	function readCssColorPref(root, prop, aliasProp, fallback) {
		var c = readCssColor(root, prop, '');
		if (c) {
			return c;
		}
		return readCssColor(root, aliasProp, fallback);
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

	function createSplit(textEl) {
		var config = {
			type: 'words',
			wordsClass: 'bw-hw__word'
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
		if (typeof ScrollTrigger === 'undefined' || !ScrollTrigger.refresh) {
			return;
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

	function whenFontsReady(cb) {
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(cb).catch(cb);
			return;
		}
		cb();
	}

	function queryHwText(root) {
		return root.querySelector('.bw-hw__text, .mws-ew-hw__text');
	}

	function applyReducedState(root) {
		if (!root) {
			return;
		}
		root.classList.add('is-reduced');
		root.classList.remove('is-ready');
		var words = root.querySelectorAll('.bw-hw__word, .mws-ew-hw__word');
		var lit = readCssColorPref(root, '--bw-hw-lit', '--mws-ew-hw-lit', '#16171B');
		Array.prototype.forEach.call(words, function (word) {
			word.style.color = lit;
		});
		if (!words.length) {
			var textEl = queryHwText(root);
			if (textEl) {
				textEl.style.color = lit;
			}
		}
	}

	function cleanupInstance(root) {
		if (!root) {
			return;
		}
		if (typeof root._bwHwCancelGsap === 'function') {
			root._bwHwCancelGsap();
			delete root._bwHwCancelGsap;
		}
		root._bwHwGen = (root._bwHwGen || 0) + 1;

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
			if (inst.textEl) {
				inst.textEl.style.width = '';
				inst.textEl.style.maxWidth = '';
			}
			if (typeof inst.unsubResize === 'function') {
				inst.unsubResize();
			}
			scopeMap.delete(root);
		}

		root.classList.remove('is-ready');
		root.classList.remove('is-reduced');
		delete root.dataset.bwHwInit;
		delete root.dataset.bwHwPending;
		root.removeAttribute('data-bw-hw-tries');
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
		if (!el || !el.closest) {
			return false;
		}
		var wrap = el.closest('.bw-horizontal-section');
		if (!wrap || wrap.classList.contains('bw-hscroll-stacked') || wrap.classList.contains('bw-hscroll-editor')) {
			return false;
		}
		return !hscrollTweenFor(el);
	}

	function bindScrollHeat(root, force) {
		if (!force && shouldDeferHscroll(root)) {
			if (root.dataset.bwHwHscrollWait === '1') {
				return;
			}
			root.dataset.bwHwHscrollWait = '1';
			var tries = 0;
			var timer = window.setInterval(function () {
				if (!shouldDeferHscroll(root) || ++tries > 50) {
					window.clearInterval(timer);
					delete root.dataset.bwHwHscrollWait;
					if (root.isConnected) {
						bindScrollHeat(root, true);
					}
				}
			}, 40);
			return;
		}

		cleanupInstance(root);
		root.dataset.bwHwInit = '1';
		var gen = root._bwHwGen || 0;

		var textEl = queryHwText(root);
		if (!textEl) {
			return;
		}

		if (prefersReducedMotion() || !canAnimate()) {
			applyReducedState(root);
			return;
		}

		whenFontsReady(function () {
			if (!root.isConnected || (root._bwHwGen || 0) !== gen) {
				return;
			}

			var start = root.getAttribute('data-start') || 'top 78%';
			var end = root.getAttribute('data-end') || 'bottom 32%';
			var scrubAttr = root.getAttribute('data-scrub');
			var staggerAttr = root.getAttribute('data-stagger');
			var scrub = scrubAttr !== null && scrubAttr !== ''
				? parseNumber(scrubAttr, 0.35)
				: readCssNumberPref(root, '--bw-hw-scrub', '--mws-ew-hw-scrub', 0.35);
			var stagger = staggerAttr !== null && staggerAttr !== ''
				? parseNumber(staggerAttr, 0.06)
				: readCssNumberPref(root, '--bw-hw-stagger', '--mws-ew-hw-stagger', 0.06);
			var lit = readCssColorPref(root, '--bw-hw-lit', '--mws-ew-hw-lit', '#16171B');
			var dim = readCssColorPref(root, '--bw-hw-dim', '--mws-ew-hw-dim', '#D7D8D4');

			prepareSplitBox(textEl);

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
				var stVars = {
					trigger: root,
					start: start,
					end: end,
					scrub: scrub <= 0 ? true : scrub
				};
				var hTween = hscrollTweenFor(root);
				if (hTween) {
					stVars.containerAnimation = hTween;
					stVars.start = 'left right';
					stVars.end = 'right left';
				}
				tl = gsap.timeline({
					scrollTrigger: stVars
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

			var unsubResize = watchBoxWidth(root, function () {
				bindScrollHeat(root, true);
			});

			if (scopeMap) {
				scopeMap.set(root, {
					tl: tl,
					st: st,
					split: split,
					textEl: textEl,
					unsubResize: unsubResize
				});
			}

			refreshScrollTrigger(150);
		});
	}

	function initInstance(root) {
		if (!root || root.dataset.bwHwInit === '1' || root.dataset.bwHwPending === '1') {
			return;
		}

		if (prefersReducedMotion()) {
			applyReducedState(root);
			root.dataset.bwHwInit = '1';
			return;
		}

		if (!canAnimate()) {
			var tries = parseInt(root.getAttribute('data-bw-hw-tries') || '0', 10);
			if (tries < 25) {
				root.setAttribute('data-bw-hw-tries', String(tries + 1));
				root.dataset.bwHwPending = '1';
				var timer = setTimeout(function () {
					delete root.dataset.bwHwPending;
					delete root._bwHwCancelGsap;
					initInstance(root);
				}, 160);
				root._bwHwCancelGsap = function () {
					clearTimeout(timer);
				};
				return;
			}
			applyReducedState(root);
			root.dataset.bwHwInit = '1';
			return;
		}

		root.removeAttribute('data-bw-hw-tries');
		bindScrollHeat(root);
	}

	function initInScope(scope) {
		var rootEl = document;
		if (scope && scope.nodeType === 1) {
			rootEl = scope;
		} else if (scope && scope.jquery && scope[0] && scope[0].nodeType === 1) {
			rootEl = scope[0];
		}
		var roots = rootEl.querySelectorAll('[data-bw-scroll-heat="1"]');
		Array.prototype.forEach.call(roots, function (root) {
			cleanupInstance(root);
			delete root.dataset.bwHwInit;
			delete root.dataset.bwHwPending;
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

	window.bwInitScrollHeat = initInScope;

	registerPlugins();
})();
