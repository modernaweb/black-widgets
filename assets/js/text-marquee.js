(function ($) {
    'use strict';

    const REBUILD_DEBOUNCE = 200;
    // Runaway guard for content-driven rebuilds only; layout-driven ones (window
    // resizing) must stay unlimited.
    const MAX_CONTENT_REBUILDS = 8;

    let scrollTriggerRefreshTimer = null;

    /**
     * Instances finish building at different times (webfonts, lazy media, tabs), and
     * every build shifts the elements below it. A single shared refresh makes sure
     * every ScrollTrigger on the page measures against the settled layout.
     */
    function scheduleScrollTriggerRefresh() {
        if (typeof ScrollTrigger === 'undefined') {
            return;
        }
        clearTimeout(scrollTriggerRefreshTimer);
        scrollTriggerRefreshTimer = setTimeout(function () {
            ScrollTrigger.refresh();
        }, 250);
    }

    function prefersReducedMotion() {
        return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
    }

    function isRtlContext() {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.config && typeof elementorFrontend.config.is_rtl !== 'undefined') {
            return !!elementorFrontend.config.is_rtl;
        }
        return document.documentElement.getAttribute('dir') === 'rtl' || document.body.classList.contains('rtl');
    }

    function disconnectObserver(observer) {
        if (!observer) {
            return;
        }
        try {
            observer.disconnect();
        } catch (e) {
            // ignore
        }
    }

    function destroyBwTextMarquee(wrapper) {
        const api = wrapper._bwTextMarquee;
        if (!api) {
            return;
        }

        clearTimeout(api.rebuildTimer);

        if (api.tween) {
            if (api.tween.scrollTrigger) {
                api.tween.scrollTrigger.kill();
            }
            api.tween.kill();
        }

        if (api.onEnter) {
            wrapper.removeEventListener('mouseenter', api.onEnter);
        }
        if (api.onLeave) {
            wrapper.removeEventListener('mouseleave', api.onLeave);
        }

        if (api.styleEl && api.styleEl.parentNode) {
            api.styleEl.parentNode.removeChild(api.styleEl);
        }

        disconnectObserver(api.visibilityObserver);
        disconnectObserver(api.sizeObserver);

        if (api.inner && api.originalInnerHtml != null) {
            api.inner.innerHTML = api.originalInnerHtml;
            api.inner.style.animation = '';
            api.inner.style.animationPlayState = '';
            api.inner.style.removeProperty('--bw-marquee-shift');
            api.inner.classList.remove('bw-marquee-initialized');
            if (typeof gsap !== 'undefined') {
                gsap.set(api.inner, { clearProps: 'transform,x,y' });
            }
        }

        wrapper.removeAttribute('data-bw-initialized');
        delete wrapper._bwTextMarquee;
    }

    /**
     * Untransformed layout width. Reading it in place keeps the inherited Elementor
     * typography - a detached copy renders at the theme's base font size instead.
     */
    function measureLayoutWidth(el) {
        const previousAnimation = el.style.animation;
        el.style.animation = 'none';
        const width = el.getBoundingClientRect().width || el.scrollWidth || 0;
        el.style.animation = previousAnimation;
        return width;
    }

    /**
     * Distance between the same edge of both sequences: the exact pixel shift that
     * lands the copy where the original started, sub-pixel widths included.
     */
    function measureSeam(first, second) {
        const shift = Math.abs(second.getBoundingClientRect().left - first.getBoundingClientRect().left);
        return shift >= 1 ? shift : first.getBoundingClientRect().width;
    }

    /**
     * Build two identical sequences. Trailing gap is on items (CSS), not between
     * halves - so pixel shift === firstSeq width and the loop never jumps.
     */
    function buildSeamlessSequences(inner, template, minWidth) {
        const seq = document.createElement('div');
        seq.className = 'bw-marquee-seq';
        seq.appendChild(template.cloneNode(true));

        inner.innerHTML = '';
        inner.appendChild(seq);

        // Measuring one unit tells us how many copies a cycle needs in a single
        // reflow, and can never stop short of the viewport the way a capped
        // clone-and-measure loop does.
        const unitWidth = seq.getBoundingClientRect().width;
        if (unitWidth >= 1 && unitWidth < minWidth) {
            const extraCopies = Math.min(Math.ceil(minWidth / unitWidth) - 1, 200);
            for (let i = 0; i < extraCopies; i += 1) {
                seq.appendChild(template.cloneNode(true));
            }
        }

        const seqClone = seq.cloneNode(true);
        seqClone.setAttribute('aria-hidden', 'true');
        inner.appendChild(seqClone);

        return measureSeam(seq, seqClone);
    }

    function setupBwTextMarquee(wrapper) {
        const inner = wrapper.querySelector('.bw-marquee-inner') || wrapper.querySelector('.bw-marquee-inner-single');
        if (!inner) {
            return;
        }

        destroyBwTextMarquee(wrapper);

        const originalInnerHtml = inner.innerHTML;
        const settings = wrapper.dataset;
        let direction = settings.direction || 'left';
        const speed = parseFloat(settings.speed);
        const resolvedSpeed = Number.isFinite(speed) && speed > 0 ? speed : 20;
        const scrollControlled = settings.scrollControlled === 'yes';
        const startCondition = settings.gsapStart || 'top bottom';
        const speedScroll = parseFloat(settings.speedScroll);
        const resolvedSpeedScroll = Number.isFinite(speedScroll) && speedScroll > 0 ? speedScroll : 2;
        const gap = parseFloat(settings.gap);
        const resolvedGap = Number.isFinite(gap) && gap >= 0 ? gap : 0;
        const pauseOnHover = wrapper.classList.contains('pause-on-hover');
        const wrapperWidth = wrapper.offsetWidth;

        wrapper.style.setProperty('--bw-marquee-gap', resolvedGap + 'px');

        if (isRtlContext()) {
            direction = direction === 'right' ? 'left' : 'right';
        }

        const api = {
            inner: inner,
            originalInnerHtml: originalInnerHtml,
            builtAtWidth: wrapperWidth,
            builtContentSize: '',
            tween: null,
            styleEl: null,
            visibilityObserver: null,
            sizeObserver: null,
            rebuildTimer: null,
            offscreenPaused: false,
            hoverPaused: false,
            onEnter: null,
            onLeave: null,
        };

        function syncCssPlayState() {
            if (!api.inner || api.tween) {
                return;
            }
            const paused = api.offscreenPaused || api.hoverPaused;
            api.inner.style.animationPlayState = paused ? 'paused' : 'running';
        }

        function attachPauseOnHover() {
            // A scroll-driven tween has no play state of its own: pausing or resuming
            // it fights ScrollTrigger's scrub and leaves it free-running.
            if (!pauseOnHover || api.tween) {
                return;
            }
            api.onEnter = function () {
                api.hoverPaused = true;
                syncCssPlayState();
            };
            api.onLeave = function () {
                api.hoverPaused = false;
                syncCssPlayState();
            };
            wrapper.addEventListener('mouseenter', api.onEnter);
            wrapper.addEventListener('mouseleave', api.onLeave);
        }

        function attachVisibilityPause() {
            // ScrollTrigger already idles outside its own range, so only the CSS
            // keyframe loop needs an offscreen pause.
            if (typeof IntersectionObserver === 'undefined' || api.tween) {
                return;
            }
            api.visibilityObserver = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        api.offscreenPaused = !entry.isIntersecting;
                        syncCssPlayState();
                    });
                },
                { root: null, rootMargin: '50px 0px', threshold: 0 }
            );
            api.visibilityObserver.observe(wrapper);
        }

        function contentUnit() {
            return inner.querySelector('.bw-text-marquee-text');
        }

        /**
         * Sub-pixel size of one repeating unit. Ancestor transforms only translate, so
         * this stays a pure layout measurement.
         */
        function contentSize() {
            const unit = contentUnit();
            if (!unit) {
                return '';
            }
            const rect = unit.getBoundingClientRect();
            return Math.round(rect.width * 100) + 'x' + Math.round(rect.height * 100);
        }

        function scheduleRebuild(layoutChanged) {
            if (!layoutChanged) {
                if ((wrapper._bwContentRebuilds || 0) >= MAX_CONTENT_REBUILDS) {
                    return;
                }
                wrapper._bwContentRebuilds = (wrapper._bwContentRebuilds || 0) + 1;
            }
            clearTimeout(api.rebuildTimer);
            api.rebuildTimer = setTimeout(function () {
                if (wrapper._bwTextMarquee !== api) {
                    return;
                }
                setupBwTextMarquee(wrapper);
            }, REBUILD_DEBOUNCE);
        }

        /**
         * Rebuild when the available width changes - including 0 -> visible, which is
         * how the widget recovers inside tabs, accordions and off-canvas panels - and
         * when the text itself resizes. A webfont swapping in or a responsive font size
         * changes the loop length while the wrapper stays exactly as wide as before.
         */
        function observeSize() {
            if (typeof ResizeObserver === 'undefined') {
                return;
            }
            api.sizeObserver = new ResizeObserver(function () {
                if (wrapper._bwTextMarquee !== api) {
                    return;
                }
                const layoutChanged = wrapper.offsetWidth !== api.builtAtWidth;
                if (!layoutChanged && contentSize() === api.builtContentSize) {
                    return;
                }
                scheduleRebuild(layoutChanged);
            });

            api.sizeObserver.observe(wrapper);
            const unit = contentUnit();
            if (unit) {
                api.sizeObserver.observe(unit);
            }
        }

        function applyPixelLoop(shift) {
            if (!(shift >= 1)) {
                return;
            }

            const keyframesName =
                'bw-tm-' + direction + '-' + Date.now() + '-' + Math.floor(Math.random() * 10000);
            // Control "left" = Left To Right: content drifts right (-shift to 0).
            const fromX = direction === 'left' ? -shift : 0;
            const toX = direction === 'left' ? 0 : -shift;
            const duration = Math.max(0.1, shift / resolvedSpeed);

            const style = document.createElement('style');
            style.setAttribute('data-bw-text-marquee', '1');
            style.textContent =
                '@keyframes ' +
                keyframesName +
                '{' +
                '0%{transform:translate3d(' +
                fromX +
                'px,0,0);}' +
                '100%{transform:translate3d(' +
                toX +
                'px,0,0);}' +
                '}';
            document.head.appendChild(style);
            api.styleEl = style;
            inner.style.setProperty('--bw-marquee-shift', shift + 'px');
            inner.style.animation = keyframesName + ' ' + duration + 's linear infinite';
        }

        function finish() {
            wrapper._bwTextMarquee = api;
            api.builtContentSize = contentSize();
            attachPauseOnHover();
            attachVisibilityPause();
            observeSize();
        }

        wrapper.setAttribute('data-bw-initialized', '1');
        inner.classList.add('bw-marquee-initialized');

        if (prefersReducedMotion()) {
            inner.style.animation = 'none';
            inner.style.transform = 'none';
            wrapper._bwTextMarquee = api;
            return;
        }

        if (inner.classList.contains('bw-marquee-inner')) {
            const template = inner.querySelector('.bw-marquee-template') || inner.querySelector('.bw-text-marquee-text');
            if (!template) {
                destroyBwTextMarquee(wrapper);
                return;
            }

            // One cycle has to outlast the visible area, or the tail of the second
            // copy runs out before the loop restarts.
            const minWidth = (wrapperWidth > 0 ? wrapperWidth : window.innerWidth || 1) * 1.2;
            const shift = buildSeamlessSequences(inner, template, minWidth);

            if (scrollControlled && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
                inner.style.animation = 'none';

                const fromX = direction === 'left' ? -shift : 0;
                const toX = direction === 'left' ? 0 : -shift;
                const travel = Math.max(shift / resolvedSpeedScroll, 1);

                api.tween = gsap.fromTo(
                    inner,
                    { x: fromX },
                    {
                        x: toX,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: wrapper,
                            start: startCondition,
                            end: function () {
                                return '+=' + travel;
                            },
                            scrub: 1.5,
                            invalidateOnRefresh: true,
                        },
                    }
                );

                scheduleScrollTriggerRefresh();
                finish();
                return;
            }

            // Without GSAP the scroll-controlled mode would render a frozen strip,
            // so fall back to the self-running loop.
            applyPixelLoop(shift);
        } else if (inner.classList.contains('bw-marquee-inner-single')) {
            const textWidth = measureLayoutWidth(inner);
            const travelWidth = wrapperWidth > 0 ? wrapperWidth : window.innerWidth || 1;

            const start = direction === 'right' ? travelWidth : -textWidth;
            const end = direction === 'right' ? -textWidth : travelWidth;
            const distance = Math.abs(end - start) || 1;
            const duration = Math.max(0.1, distance / resolvedSpeed);
            const keyframesName =
                'bw-marquee-' + direction + '-' + Date.now() + '-' + Math.floor(Math.random() * 10000);
            const style = document.createElement('style');
            style.setAttribute('data-bw-text-marquee', '1');
            style.textContent =
                '@keyframes ' +
                keyframesName +
                '{' +
                '0%{transform:translate3d(' +
                start +
                'px,0,0);}' +
                '100%{transform:translate3d(' +
                end +
                'px,0,0);}' +
                '}';
            document.head.appendChild(style);
            api.styleEl = style;
            inner.style.animation = keyframesName + ' ' + duration + 's linear infinite';
        }

        finish();
    }

    function initBwTextMarquee($scope) {
        $scope.find('.bw-text-marquee-wrapper').each(function () {
            setupBwTextMarquee(this);
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/b_text_marquee.default',
            initBwTextMarquee
        );
    });
})(jQuery);
