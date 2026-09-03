(function ($) {
    'use strict';

    function prefersReducedMotion() {
        return (
            typeof window.matchMedia === 'function' &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches
        );
    }

    function readBoolAttr($el, name) {
        var raw = $el.attr('data-' + name);
        return raw === 'true' || raw === '1' || raw === 'yes';
    }

    function readNumberAttr($el, name, fallback) {
        var raw = $el.attr('data-' + name);
        if (raw === undefined || raw === null || raw === '') {
            return fallback;
        }
        var n = parseFloat(raw);
        return isNaN(n) ? fallback : n;
    }

    function destroyBwSwiper(el) {
        if (!el) {
            return;
        }

        if (el._bwSwiperIO) {
            try {
                el._bwSwiperIO.disconnect();
            } catch (e) {
                // ignore
            }
            el._bwSwiperIO = null;
        }

        if (el._bwDragGuards && typeof el._bwDragGuards.detach === 'function') {
            try {
                el._bwDragGuards.detach();
            } catch (e) {
                // ignore
            }
            el._bwDragGuards = null;
        }

        if (el._bwSwiper && typeof el._bwSwiper.destroy === 'function') {
            try {
                el._bwSwiper.destroy(true, true);
            } catch (e) {
                // ignore destroy errors on detached nodes
            }
            el._bwSwiper = null;
        }

        if (el._bwSwiperOriginalHtml != null) {
            var wrapper = el.querySelector('.bw-swiper-wrapper, .swiper-wrapper');
            if (wrapper) {
                wrapper.innerHTML = el._bwSwiperOriginalHtml;
            }
            el._bwSwiperOriginalHtml = null;
        }

        el.classList.remove('swiper-initialized', 'bw-is-dragging', 'bw-swiper--few', 'bw-swiper-center-single', 'bw-swiper-peek-progress');
        delete el.swiper;
        delete el._bwOriginalSlideCount;
        delete el._bwJustDragged;
    }

    function attachAutoplayVisibility(el, instance) {
        if (!instance || !instance.autoplay || typeof IntersectionObserver === 'undefined') {
            return;
        }

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!el._bwSwiper || !el._bwSwiper.autoplay) {
                        return;
                    }
                    if (entry.isIntersecting) {
                        el._bwSwiper.autoplay.start();
                    } else {
                        el._bwSwiper.autoplay.stop();
                    }
                });
            },
            { root: null, rootMargin: '0px', threshold: 0.15 }
        );

        observer.observe(el);
        el._bwSwiperIO = observer;
    }

    function attachDragGuards(el, instance) {
        if (!instance || typeof instance.on !== 'function') {
            return;
        }

        // Elementor re-inits widgets often - drop any prior DOM listeners first.
        if (el._bwDragGuards && typeof el._bwDragGuards.detach === 'function') {
            el._bwDragGuards.detach();
            el._bwDragGuards = null;
        }

        var clearTimer = null;
        var didDrag = false;
        var scrollLockX = 0;
        var scrollLockY = 0;
        var scrollLockActive = false;

        function readScrollPos() {
            scrollLockX = window.scrollX || window.pageXOffset || 0;
            scrollLockY = window.scrollY || window.pageYOffset || 0;
        }

        function restoreScrollPos() {
            if (!scrollLockActive) {
                return;
            }
            window.scrollTo(scrollLockX, scrollLockY);
        }

        function setDragging(active) {
            if (clearTimer) {
                clearTimeout(clearTimer);
                clearTimer = null;
            }
            if (active) {
                el.classList.add('bw-is-dragging');
                try {
                    if (window.getSelection) {
                        window.getSelection().removeAllRanges();
                    }
                } catch (e) {
                    // ignore
                }
            } else {
                clearTimer = setTimeout(function () {
                    el.classList.remove('bw-is-dragging');
                    clearTimer = null;
                }, 50);
            }
        }

        function onTouchStart() {
            didDrag = false;
            readScrollPos();
        }

        function onFirstMove() {
            didDrag = true;
            el._bwJustDragged = true;
            readScrollPos();
            scrollLockActive = true;
            setDragging(true);
        }

        function onMove() {
            if (!didDrag) {
                return;
            }
            el._bwJustDragged = true;
            setDragging(true);
        }

        function onTouchEnd() {
            restoreScrollPos();
            setDragging(false);
            if (didDrag) {
                el._bwJustDragged = true;
                setTimeout(function () {
                    el._bwJustDragged = false;
                    scrollLockActive = false;
                }, 120);
            } else {
                el._bwJustDragged = false;
                scrollLockActive = false;
            }
            didDrag = false;
        }

        function onTransitionEnd() {
            restoreScrollPos();
            setDragging(false);
        }

        function onFocusIn() {
            if (el.classList.contains('bw-is-dragging') || el._bwJustDragged) {
                restoreScrollPos();
                requestAnimationFrame(restoreScrollPos);
            }
        }

        function onClickCapture(event) {
            if (!el._bwJustDragged) {
                return;
            }
            var link = event.target && event.target.closest
                ? event.target.closest('a')
                : null;
            if (link && el.contains(link)) {
                event.preventDefault();
                event.stopPropagation();
            }
        }

        // Lock scroll only after Swiper confirms a horizontal swipe - otherwise
        // vertical page scroll starting on the carousel is blocked.
        instance.on('touchStart', onTouchStart);
        instance.on('sliderFirstMove', onFirstMove);
        instance.on('sliderMove', onMove);
        instance.on('touchEnd', onTouchEnd);
        instance.on('transitionEnd', onTransitionEnd);

        el.addEventListener('focusin', onFocusIn, true);
        el.addEventListener('click', onClickCapture, true);

        el._bwDragGuards = {
            detach: function () {
                if (clearTimer) {
                    clearTimeout(clearTimer);
                    clearTimer = null;
                }
                if (instance && typeof instance.off === 'function') {
                    instance.off('touchStart', onTouchStart);
                    instance.off('sliderFirstMove', onFirstMove);
                    instance.off('sliderMove', onMove);
                    instance.off('touchEnd', onTouchEnd);
                    instance.off('transitionEnd', onTransitionEnd);
                }
                el.removeEventListener('focusin', onFocusIn, true);
                el.removeEventListener('click', onClickCapture, true);
                el.classList.remove('bw-is-dragging');
                scrollLockActive = false;
                didDrag = false;
            }
        };
    }

    function escapeHtmlAttr(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function isSafeMediaUrl(url) {
        if (!url || typeof url !== 'string') {
            return false;
        }
        return /^(https?:)?\/\//i.test(url) || url.charAt(0) === '/';
    }

    function getTypeOptions(type) {
        var options = {
            spaceBetween: 16,
            centeredSlides: false,
        };

        switch (type) {
            case 'type2':
                options.spaceBetween = 24;
                break;
            case 'type3':
                options.spaceBetween = 12;
                options.centeredSlides = true;
                break;
            case 'type4':
                options.spaceBetween = 20;
                options.centeredSlides = true;
                break;
            case 'type1':
            default:
                options.spaceBetween = 16;
                break;
        }

        return options;
    }

    /**
     * Mirrors Swiper's own internal loopFix requirement (verified against the
     * bundled swiper-bundle.min.js): Swiper needs at least
     * ceil(slidesPerView) [bumped to odd when centeredSlides + even] +
     * slidesPerGroup + loopAdditionalSlides *real* slides in the DOM, or it
     * logs "not enough slides for loop mode" and the loop visibly glitches
     * (empty-looking slide / jump at the wrap point). loopAdditionalSlides is
     * kept at Swiper's default 0 - raising it inflates this requirement and
     * forces needless duplicate slides. Checked per breakpoint.
     */
    function requiredLoopSlideCount(slidesPerView, slidesPerGroup, centeredSlides) {
        var f = Math.ceil(slidesPerView);
        if (centeredSlides && f % 2 === 0) {
            f += 1;
        }
        return f + Math.max(1, slidesPerGroup);
    }

    function greatestCommonDivisor(a, b) {
        while (b) {
            var t = b;
            b = a % b;
            a = t;
        }
        return a;
    }

    function leastCommonMultiple(a, b) {
        if (!a || !b) {
            return Math.max(1, a || b || 1);
        }
        return Math.abs(a * b) / greatestCommonDivisor(a, b);
    }

    /**
     * How many copies of the *whole* item set the loop needs.
     *
     * Two separate constraints, both of which make items visibly disappear or
     * repeat unevenly when ignored:
     *  1. Swiper needs `minSlides` nodes (see requiredLoopSlideCount).
     *  2. Swiper's loopCreate appends empty `.swiper-slide-blank` nodes when
     *     the slide count is not divisible by slidesPerGroup - growing the
     *     multiple instead keeps the track free of blank gaps.
     */
    function resolveLoopCopies(originalCount, minSlides, groupSize) {
        var copies = Math.max(1, Math.ceil(minSlides / originalCount));
        var guard = 0;
        while (groupSize > 1 && (originalCount * copies) % groupSize !== 0 && guard < 24) {
            copies += 1;
            guard += 1;
        }
        return copies;
    }

    /**
     * Cap slidesPerView so 1-2 items never fight responsive settings
     * (e.g. desktop=3 with only 2 slides).
     */
    function capPerView(value, originalCount, peekMin) {
        var n = Math.min(10, Math.max(1, value));
        if (originalCount < 1) {
            return 1;
        }
        // Never ask Swiper to show more than we have (before loop clones).
        n = Math.min(n, originalCount);
        if (peekMin && originalCount >= 2) {
            // Peek only when there is at least one neighbor slide.
            n = Math.min(originalCount, Math.max(n, peekMin));
        }
        return n;
    }

    /**
     * centeredSlides + slidesPerView >= slide count (no loop) shifts the track
     * and clips edge slides - you only "see" ~2 of 3. Disable centering when
     * every slide already fits; keep it for peek/editorial when extras exist.
     */
    function resolveCenteredSlides(wantCentered, loop, perView, slideCount) {
        if (!wantCentered) {
            return false;
        }
        if (loop) {
            return true;
        }
        if (slideCount <= 1) {
            return true;
        }
        if (perView >= slideCount) {
            return false;
        }
        return true;
    }

    /**
     * Strip id="" attributes from a cloned slide's markup (and any nested
     * elements) so loop clones never create duplicate DOM ids - this matters
     * most for "Elementor Template" slides, which can contain nested widgets
     * with their own ids. Visibility/focusability of clones is intentionally
     * left to Swiper's own a11y module (clones do become the real, focused
     * slide as the user scrolls through the loop, so they must stay
     * accessible - they are not purely decorative padding).
     */
    function stripDuplicateIds(html) {
        if (typeof document === 'undefined' || typeof document.createElement !== 'function') {
            return html;
        }

        var holder = document.createElement('div');
        holder.innerHTML = html;
        var root = holder.firstElementChild;
        if (!root) {
            return html;
        }

        if (root.hasAttribute('id')) {
            root.removeAttribute('id');
        }
        var withIds = root.querySelectorAll('[id]');
        for (var i = 0; i < withIds.length; i++) {
            withIds[i].removeAttribute('id');
        }

        return root.outerHTML;
    }

    /**
     * Repeat the *whole* item set `copies` times so Swiper's loop has enough
     * nodes. Topping the track up with a partial set (just the 2-3 extra nodes
     * Swiper technically needs) is what makes a carousel look like it is
     * skipping items: the first few items then appear twice per revolution and
     * the rest once. Whole-set copies keep every item on screen equally often.
     *
     * Works for 1+ items (a single item is simply repeated).
     */
    function repeatSlidesForLoop(el, copies) {
        var wrapper = el.querySelector(':scope > .bw-swiper-wrapper, :scope > .swiper-wrapper');
        if (!wrapper) {
            wrapper = el.querySelector('.bw-swiper-wrapper, .swiper-wrapper');
        }
        if (!wrapper) {
            return 0;
        }

        // Direct children only - nested carousels must not inflate slide counts.
        var originals = wrapper.querySelectorAll(':scope > .bw-swiper-slide, :scope > .swiper-slide');
        if (!originals.length) {
            originals = wrapper.querySelectorAll('.bw-swiper-slide, .swiper-slide');
        }
        var count = originals.length;
        if (count < 1) {
            return 0;
        }

        if (copies <= 1) {
            return count;
        }

        if (el._bwSwiperOriginalHtml == null) {
            el._bwSwiperOriginalHtml = wrapper.innerHTML;
        }

        var sourceHtml = [];
        for (var i = 0; i < originals.length; i++) {
            sourceHtml.push(stripDuplicateIds(originals[i].outerHTML));
        }

        var extraSets = Math.min(copies - 1, 23);
        for (var c = 0; c < extraSets; c++) {
            wrapper.insertAdjacentHTML('beforeend', sourceHtml.join(''));
        }

        return count * (extraSets + 1);
    }

    function buildBulletHtml(type, className, label, customDot) {
        if (customDot && isSafeMediaUrl(customDot)) {
            // Rendered as a CSS mask (not <img>) so the Bullet Color controls
            // can still recolor the icon for both inactive/active states.
            // Defensive: a literal single quote in the URL could otherwise
            // break out of the CSS url('...') wrapper below.
            var safeDotUrl = customDot.replace(/'/g, '%27');
            var style = "--bw-dot-icon-url:url('" + safeDotUrl + "')";
            return (
                '<button type="button" class="' +
                escapeHtmlAttr(className) +
                ' bw-swiper-pagination-bullet--icon" aria-label="' +
                escapeHtmlAttr(label) +
                '"><span class="bw-swiper-pagination-bullet-icon" style="' +
                escapeHtmlAttr(style) +
                '" aria-hidden="true"></span></button>'
            );
        }

        if (type === 'type4') {
            return (
                '<button type="button" class="' +
                escapeHtmlAttr(className) +
                '" aria-label="' +
                escapeHtmlAttr(label) +
                '"></button>'
            );
        }

        return (
            '<button type="button" class="' +
            escapeHtmlAttr(className) +
            '" aria-label="' +
            escapeHtmlAttr(label) +
            '"><svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="6" stroke-width="1" fill="none"/></svg></button>'
        );
    }

    /**
     * Toggle a class on the root element when there is a genuine single
     * centered slide for the *current* breakpoint (centeredSlides on AND the
     * rounded effective slidesPerView is odd, or a peek view under 2).
     * Drives the optional Peek Focus "Center Slide Emphasis" scale-up.
     */
    function updateCenterSingleClass(el, instance) {
        if (!instance || !instance.params) {
            return;
        }
        var spv = instance.params.slidesPerView;
        var numeric = typeof spv === 'number' ? spv : parseFloat(spv);
        var hasSingleCenter =
            !!instance.params.centeredSlides &&
            !isNaN(numeric) &&
            numeric > 0 &&
            (numeric < 2 || Math.round(numeric) % 2 === 1);
        el.classList.toggle('bw-swiper-center-single', hasSingleCenter);
    }

    /**
     * Peek Focus: drive scale/opacity from each slide's Swiper progress so the
     * effect stays smooth while dragging (class-based active CSS snaps).
     */
    function attachPeekProgressFx(el, instance, peekCenterEmphasis) {
        if (!el || !instance || typeof instance.on !== 'function') {
            return;
        }

        el.classList.add('bw-swiper-peek-progress');

        var minScale = 0.92;
        var minOpacity = 0.45;

        function readMaxScale() {
            var maxScale = 1;
            if (peekCenterEmphasis && el.classList.contains('bw-swiper-center-single')) {
                var host = (el.closest && el.closest('.bw-swiper-outer')) || el;
                var raw = '';
                try {
                    raw = window.getComputedStyle(host).getPropertyValue('--bw-peek-center-scale').trim();
                } catch (e) {
                    raw = '';
                }
                var n = parseFloat(raw);
                if (!isNaN(n) && n >= 1) {
                    maxScale = n;
                } else {
                    maxScale = 1.1;
                }
            }
            return maxScale;
        }

        function applyPeekProgress() {
            var slides = instance.slides;
            if (!slides || !slides.length) {
                return;
            }
            var maxScale = readMaxScale();
            for (var i = 0; i < slides.length; i++) {
                var slide = slides[i];
                if (!slide || !slide.style) {
                    continue;
                }
                if (slide.classList.contains('swiper-slide-blank')) {
                    continue;
                }
                var progress = typeof slide.progress === 'number' ? slide.progress : 0;
                var t = Math.min(1, Math.abs(progress));
                // Soft falloff so neighbors ease instead of linear snap.
                t = t * t * (3 - 2 * t);
                var scale = maxScale + (minScale - maxScale) * t;
                var opacity = 1 + (minOpacity - 1) * t;
                slide.style.transform = 'scale(' + scale + ')';
                slide.style.opacity = String(opacity);
            }
        }

        instance.on('setTranslate', applyPeekProgress);
        instance.on('setTransition', function (a, b) {
            var duration = typeof b === 'number' ? b : typeof a === 'number' ? a : 0;
            var slides = instance.slides;
            if (slides) {
                for (var i = 0; i < slides.length; i++) {
                    if (slides[i] && slides[i].style) {
                        slides[i].style.transitionDuration = '0ms';
                    }
                }
            }
            // Re-apply while Swiper animates the track (arrows / pagination / snap).
            if (duration > 0 && typeof requestAnimationFrame === 'function') {
                var end = Date.now() + duration + 32;
                function tick() {
                    applyPeekProgress();
                    if (Date.now() < end && el._bwSwiper === instance) {
                        requestAnimationFrame(tick);
                    }
                }
                requestAnimationFrame(tick);
            }
        });
        instance.on('slideChangeTransitionEnd', applyPeekProgress);
        instance.on('breakpoint', function () {
            updateCenterSingleClass(el, instance);
            applyPeekProgress();
        });
        instance.on('resize', applyPeekProgress);
        if (typeof instance.updateSlidesProgress === 'function') {
            try {
                instance.updateSlidesProgress();
            } catch (e) {
                // ignore
            }
        }
        applyPeekProgress();
    }

    /**
     * Pagination/nav elements normally live inside .bw-swiper, but the
     * "Outside" position setting renders them as siblings under the
     * .bw-swiper-outer wrapper instead (so Swiper's own overflow:hidden on
     * .bw-swiper can't clip them). Searching from the outer wrapper - or
     * .bw-swiper itself as a defensive fallback - finds them either way.
     */
    function getNavPaginationScope(el) {
        return (el.closest && el.closest('.bw-swiper-outer')) || el.parentElement || el;
    }

    /**
     * Progressbar can fail silently when the pagination el lives outside
     * `.swiper` (Outside position): Swiper's size selectors never match and
     * the lock class can hide it. Force a visible track + fill, then update.
     */
    function ensureProgressbarPagination(instance, pagEl) {
        if (!instance || !pagEl) {
            return;
        }
        pagEl.classList.add('swiper-pagination-progressbar', 'bw-swiper-pagination--progressbar');
        pagEl.classList.remove('swiper-pagination-lock', 'swiper-pagination-hidden', 'swiper-pagination-bullets');
        pagEl.style.display = 'block';
        pagEl.style.visibility = 'visible';
        pagEl.removeAttribute('hidden');

        var fill = pagEl.querySelector('.swiper-pagination-progressbar-fill');
        if (!fill) {
            fill = document.createElement('span');
            fill.className = 'swiper-pagination-progressbar-fill';
            pagEl.appendChild(fill);
        }

        try {
            if (instance.pagination) {
                if (typeof instance.pagination.render === 'function') {
                    instance.pagination.render();
                }
                if (typeof instance.pagination.update === 'function') {
                    instance.pagination.update();
                }
            }
            if (typeof instance.update === 'function') {
                instance.update();
            }
        } catch (e) {
            // ignore
        }

        // If Swiper still left fill at scale(0) with no progress (e.g. 1 slide),
        // show a minimal filled state so the bar is obviously present.
        fill = pagEl.querySelector('.swiper-pagination-progressbar-fill');
        if (fill) {
            var transform = '';
            try {
                transform = fill.style.transform || window.getComputedStyle(fill).transform || '';
            } catch (e2) {
                transform = fill.style.transform || '';
            }
            if (!transform || transform === 'none' || /scale(?:X|3d)?\(\s*0/.test(transform)) {
                var total = 1;
                try {
                    total = (instance.slides && instance.slides.length) || 1;
                    if (typeof instance.snapGrid !== 'undefined' && instance.snapGrid.length) {
                        total = Math.max(1, instance.snapGrid.length);
                    }
                } catch (e3) {
                    total = 1;
                }
                var current = 1;
                try {
                    current = (typeof instance.realIndex === 'number' ? instance.realIndex : instance.activeIndex) + 1;
                } catch (e4) {
                    current = 1;
                }
                var ratio = Math.max(0.08, Math.min(1, current / Math.max(1, total)));
                fill.style.transform = 'scaleX(' + ratio + ')';
            }
        }
    }

    /**
     * Keep pagination bullet count = unique slides even when DOM was cloned for loop.
     */
    function attachFewSlidePagination(el, instance, originalCount, type) {
        if (!instance || originalCount < 1) {
            return;
        }

        var pagEl = getNavPaginationScope(el).querySelector('.bw-swiper-pagination');
        if (!pagEl) {
            return;
        }

        function realIndex() {
            var idx = typeof instance.realIndex === 'number' ? instance.realIndex : instance.activeIndex;
            return ((idx % originalCount) + originalCount) % originalCount;
        }

        function syncBullets() {
            var bullets = pagEl.querySelectorAll('.swiper-pagination-bullet');
            var real = realIndex();
            for (var i = 0; i < bullets.length; i++) {
                if (i >= originalCount) {
                    bullets[i].style.display = 'none';
                    bullets[i].setAttribute('aria-hidden', 'true');
                    bullets[i].classList.remove('swiper-pagination-bullet-active');
                    continue;
                }
                bullets[i].style.display = '';
                bullets[i].removeAttribute('aria-hidden');
                bullets[i].classList.toggle('swiper-pagination-bullet-active', i === real);
            }
        }

        instance.on('slideChange', syncBullets);
        instance.on('slideChangeTransitionEnd', syncBullets);
        instance.on('resize', syncBullets);
        syncBullets();

        // Clicking a visible bullet should jump to that unique slide.
        pagEl.addEventListener('click', function (event) {
            var btn = event.target.closest('.swiper-pagination-bullet');
            if (!btn || !pagEl.contains(btn)) {
                return;
            }
            var bullets = Array.prototype.slice.call(
                pagEl.querySelectorAll('.swiper-pagination-bullet')
            );
            var index = bullets.indexOf(btn);
            if (index < 0 || index >= originalCount) {
                return;
            }
            event.preventDefault();
            event.stopPropagation();
            if (typeof instance.slideToLoop === 'function' && instance.params.loop) {
                instance.slideToLoop(index);
            } else {
                instance.slideTo(index);
            }
        }, true);
    }

    function initBwSwiper($scope, skipInitialized) {
        // Every .bw-swiper is always rendered as a direct child of its own
        // .bw-swiper-outer wrapper (regardless of how many Elementor wrapper
        // levels sit above that, e.g. .elementor-widget-container) - and never
        // nested inside another .bw-swiper (a template used as a slide could
        // in theory contain its own carousel widget, which must init separately).
        var $carousels = $scope.find('.bw-swiper-outer > .bw-swiper').filter(function () {
            if (skipInitialized && this._bwSwiper) {
                return false;
            }
            return !jQuery(this).parents('.bw-swiper').length;
        });

        $carousels.each(function () {
            var el = this;
            var $this = $(el);

            destroyBwSwiper(el);

            if (typeof Swiper === 'undefined') {
                return;
            }

            var wrapper = el.querySelector(':scope > .bw-swiper-wrapper, :scope > .swiper-wrapper')
                || el.querySelector('.bw-swiper-wrapper, .swiper-wrapper');
            var originalCount = wrapper
                ? wrapper.querySelectorAll(':scope > .bw-swiper-slide, :scope > .swiper-slide').length
                : 0;
            if (!originalCount && wrapper) {
                originalCount = wrapper.querySelectorAll('.bw-swiper-slide, .swiper-slide').length;
            }
            var declaredCount = parseInt($this.attr('data-slide-count'), 10);
            if (!isNaN(declaredCount) && declaredCount > 0) {
                originalCount = declaredCount;
            }
            el._bwOriginalSlideCount = originalCount;

            if (originalCount < 1) {
                return;
            }

            var slidesDesktop = readNumberAttr($this, 'slides-per-view-desktop', 3);
            var slidesTablet = readNumberAttr($this, 'slides-per-view-tablet', 2);
            var slidesMobile = readNumberAttr($this, 'slides-per-view-mobile', 1);
            var slidesToScroll = Math.max(1, parseInt(readNumberAttr($this, 'slides-to-scroll', 1), 10) || 1);
            var autoplay = readBoolAttr($this, 'autoplay');
            var autoplaySpeed = Math.max(100, parseInt(readNumberAttr($this, 'autoplay-speed', 3000), 10) || 3000);
            var transitionSpeed = Math.max(
                200,
                Math.min(2500, parseInt(readNumberAttr($this, 'transition-speed', 750), 10) || 750)
            );
            var loop = readBoolAttr($this, 'loop');
            var pauseOnHover = readBoolAttr($this, 'pause-on-hover');
            var pagination = readBoolAttr($this, 'pagination');
            var paginationType = $this.attr('data-pagination-type') || 'dots';
            if (['dots', 'fraction', 'progressbar'].indexOf(paginationType) === -1) {
                paginationType = 'dots';
            }
            var navigation = readBoolAttr($this, 'navigation');
            var peekCenterEmphasis = readBoolAttr($this, 'peek-center-emphasis');
            var type = $this.attr('data-type') || 'type1';
            var spaceBetweenOverride = $this.attr('data-space-between');
            var spaceBetweenParsed =
                spaceBetweenOverride === undefined || spaceBetweenOverride === ''
                    ? NaN
                    : parseInt(spaceBetweenOverride, 10);

            if (prefersReducedMotion()) {
                autoplay = false;
            }

            var typeOptions = getTypeOptions(type);
            var fewSlides = originalCount <= 2;
            if (fewSlides) {
                el.classList.add('bw-swiper--few');
            }

            // Responsive per-view - capped to item count (no conflict with 1-2 items).
            // Type4 peek only applies to a single-focus view (slidesPerView <= 1).
            // On 2-3-up layouts it clips whole slides.
            var peekMobile = type === 'type4' && slidesMobile <= 1 ? 1.15 : 0;
            var peekTablet = type === 'type4' && slidesTablet <= 1 ? 1.25 : 0;
            var peekDesktop = type === 'type4' && slidesDesktop <= 1 ? 1.35 : 0;

            var bpMobile = capPerView(slidesMobile, originalCount, peekMobile);
            var bpTablet = capPerView(slidesTablet, originalCount, peekTablet);
            var bpDesktop = capPerView(slidesDesktop, originalCount, peekDesktop);

            // Single item: always one full slide, no peek/half-empty track.
            if (originalCount === 1) {
                bpMobile = 1;
                bpTablet = 1;
                bpDesktop = 1;
                typeOptions.centeredSlides = true;
            }

            slidesToScroll = Math.min(slidesToScroll, originalCount);

            var wantCentered = !!typeOptions.centeredSlides;

            var groupMobile = Math.min(slidesToScroll, Math.max(1, Math.floor(bpMobile)));
            var groupTablet = Math.min(slidesToScroll, Math.max(1, Math.floor(bpTablet)));
            var groupDesktop = Math.min(slidesToScroll, Math.max(1, Math.floor(bpDesktop)));

            // Real minimum required by Swiper's own loopFix (see requiredLoopSlideCount
            // docblock) - checked across every breakpoint, since the DOM slide count is
            // fixed regardless of viewport. Assume centered whenever the type wants it
            // (strictest), since centering bumps slidesPerView to the next odd number.
            var minLoopSlides = loop
                ? Math.max(
                      requiredLoopSlideCount(bpMobile, groupMobile, wantCentered),
                      requiredLoopSlideCount(bpTablet, groupTablet, wantCentered),
                      requiredLoopSlideCount(bpDesktop, groupDesktop, wantCentered)
                  )
                : 0;

            // slidesPerGroup differs per breakpoint but the DOM node count does not,
            // so the total has to divide by all three to stay blank-slide free.
            var groupLcm = leastCommonMultiple(leastCommonMultiple(groupMobile, groupTablet), groupDesktop);

            // Infinite loop must work with 1+ items - repeat the item set as needed.
            var slideCount = originalCount;
            if (loop) {
                slideCount = repeatSlidesForLoop(el, resolveLoopCopies(originalCount, minLoopSlides, groupLcm));
                if (slideCount < 2) {
                    // Empty after a failed repeat - disable loop only then.
                    loop = false;
                }
            }

            var centerMobile = resolveCenteredSlides(wantCentered, loop, bpMobile, originalCount);
            var centerTablet = resolveCenteredSlides(wantCentered, loop, bpTablet, originalCount);
            var centerDesktop = resolveCenteredSlides(wantCentered, loop, bpDesktop, originalCount);

            var navPaginationScope = getNavPaginationScope(el);
            var pagEl = navPaginationScope.querySelector('.bw-swiper-pagination');
            var nextEl = navPaginationScope.querySelector('.bw-swiper-button-next');
            var prevEl = navPaginationScope.querySelector('.bw-swiper-button-prev');

            var swiperOptions = {
                slidesPerView: bpMobile,
                slidesPerGroup: groupMobile,
                centeredSlides: centerMobile,
                // Without loop, keep edge slides fully inside the viewport instead of
                // half-clipping them (common on Editorial / Peek Focus with 3 items).
                centeredSlidesBounds: !loop && wantCentered,
                // Fractional slidesPerView (Peek 1.2-1.5) jumps with roundLengths.
                roundLengths: type !== 'type4',
                // Needed for smooth Peek Focus scale/opacity while dragging.
                watchSlidesProgress: type === 'type4',
                breakpoints: {
                    768: {
                        slidesPerView: bpTablet,
                        slidesPerGroup: groupTablet,
                        centeredSlides: centerTablet,
                        centeredSlidesBounds: !loop && wantCentered,
                    },
                    1025: {
                        slidesPerView: bpDesktop,
                        slidesPerGroup: groupDesktop,
                        centeredSlides: centerDesktop,
                        centeredSlidesBounds: !loop && wantCentered,
                    },
                },
                loop: loop,
                loopAdditionalSlides: 0,
                // The track is already sized to a whole number of item sets, so Swiper
                // must never pad it with empty slides.
                loopAddBlankSlides: false,
                rewind: !loop && originalCount > 1,
                // With 1-2 items + loop clones, don't let watchOverflow kill interaction.
                watchOverflow: !loop && originalCount <= 1,
                grabCursor: originalCount > 1 || loop,
                simulateTouch: true,
                allowTouchMove: originalCount > 1 || loop,
                touchStartPreventDefault: false,
                threshold: 4,
                resistanceRatio: 0.85,
                followFinger: true,
                longSwipesRatio: 0.35,
                speed: prefersReducedMotion() ? 0 : transitionSpeed,
                keyboard: {
                    enabled: originalCount > 1 || loop,
                    onlyInViewport: true,
                },
                autoplay:
                    autoplay && (loop || originalCount > 1)
                        ? {
                              delay: autoplaySpeed,
                              pauseOnMouseEnter: pauseOnHover,
                              disableOnInteraction: false,
                          }
                        : false,
                spaceBetween:
                    !isNaN(spaceBetweenParsed) && spaceBetweenParsed >= 0
                        ? spaceBetweenParsed
                        : originalCount === 1
                          ? 0
                          : typeOptions.spaceBetween,
                a11y: {
                    enabled: true,
                    // Swiper 11: focusing the active slide must not scroll the page.
                    scrollOnFocus: false,
                    prevSlideMessage: $this.attr('data-prev-label') || 'Previous slide',
                    nextSlideMessage: $this.attr('data-next-label') || 'Next slide',
                    paginationBulletMessage: $this.attr('data-bullet-label') || 'Go to slide {{index}}',
                },
                uniqueNavElements: true,
            };

            if (pagination && pagEl) {
                if (paginationType === 'fraction') {
                    // Loop-clone padding can inflate Swiper's own slide count beyond the
                    // unique originals - remap the displayed numbers back to originalCount.
                    swiperOptions.pagination = {
                        el: pagEl,
                        type: 'fraction',
                        formatFractionCurrent: function (number) {
                            return String(((number - 1) % originalCount) + 1);
                        },
                        formatFractionTotal: function () {
                            return String(originalCount);
                        },
                    };
                } else if (paginationType === 'progressbar') {
                    swiperOptions.pagination = {
                        el: pagEl,
                        type: 'progressbar',
                    };
                    // Seed Swiper classes early so CSS sizes apply even before init.
                    pagEl.classList.add('swiper-pagination-progressbar', 'bw-swiper-pagination--progressbar');
                } else {
                    swiperOptions.pagination = {
                        el: pagEl,
                        type: 'bullets',
                        clickable: true,
                        renderBullet: function (index, className) {
                            var template = $this.attr('data-bullet-label') || 'Go to slide {{index}}';
                            var label = template.replace('{{index}}', String(index + 1));
                            var customDot = $this.attr('data-custom-dot-icon') || '';
                            return buildBulletHtml(type, className, label, customDot);
                        },
                    };
                }
            }

            if (navigation && nextEl && prevEl) {
                swiperOptions.navigation = {
                    nextEl: nextEl,
                    prevEl: prevEl,
                };
            }

            try {
                var instance = new Swiper(el, swiperOptions);
                el._bwSwiper = instance;
                attachDragGuards(el, instance);

                if (pagination && paginationType === 'dots' && (loop || fewSlides)) {
                    attachFewSlidePagination(el, instance, originalCount, type);
                }

                if (pagination && paginationType === 'progressbar' && pagEl) {
                    ensureProgressbarPagination(instance, pagEl);
                    setTimeout(function () {
                        if (el._bwSwiper === instance) {
                            ensureProgressbarPagination(instance, pagEl);
                        }
                    }, 120);
                    setTimeout(function () {
                        if (el._bwSwiper === instance) {
                            ensureProgressbarPagination(instance, pagEl);
                        }
                    }, 450);
                }

                if (swiperOptions.autoplay) {
                    attachAutoplayVisibility(el, instance);
                }

                if (type === 'type4') {
                    updateCenterSingleClass(el, instance);
                    attachPeekProgressFx(el, instance, peekCenterEmphasis);
                    instance.on('breakpoint', function () {
                        updateCenterSingleClass(el, instance);
                    });
                    instance.on('resize', function () {
                        updateCenterSingleClass(el, instance);
                    });
                }

                // Elementor preview often inits before the column has a real width -
                // force layout recalculation so slides are not 0-width / invisible.
                refreshBwSwiperLayout(el, instance);
            } catch (err) {
                el.classList.remove('swiper-initialized');
                el._bwSwiper = null;
            }
        });
    }

    function refreshBwSwiperLayout(el, instance) {
        if (!instance || typeof instance.update !== 'function') {
            return;
        }
        function tick() {
            if (!el._bwSwiper || el._bwSwiper !== instance) {
                return;
            }
            try {
                instance.update();
                if (typeof instance.updateSlides === 'function') {
                    instance.updateSlides();
                }
                if (typeof instance.updateSlidesClasses === 'function') {
                    instance.updateSlidesClasses();
                }
            } catch (e) {
                // ignore
            }
        }
        tick();
        if (typeof requestAnimationFrame === 'function') {
            requestAnimationFrame(tick);
        }
        setTimeout(tick, 100);
        setTimeout(tick, 400);
        // Recalc once images finish loading (common blank-slider cause in editor).
        var imgs = el.querySelectorAll('img');
        for (var i = 0; i < imgs.length; i++) {
            if (!imgs[i].complete) {
                imgs[i].addEventListener('load', tick, { once: true });
            }
        }
    }

    // Editor preview / MutationObserver re-init.
    window.bwInitImageCarousel = function (scope) {
        var $scope = scope && scope.jquery ? scope : jQuery(scope || document);
        initBwSwiper($scope);
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/b_image_carousel.default',
            function ($scope) {
                initBwSwiper($scope);
            }
        );
    });

    /**
     * Safety net for carousels that frontend/element_ready never reaches -
     * widgets printed inside a loop grid / popup / saved template, or a theme
     * that loads Elementor's frontend bundle after this file. Already-running
     * instances are skipped so a sweep never resets position or autoplay.
     */
    function sweepDocument() {
        initBwSwiper(jQuery(document), true);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', sweepDocument);
    } else {
        sweepDocument();
    }
    $(window).on('load', sweepDocument);
})(jQuery);
