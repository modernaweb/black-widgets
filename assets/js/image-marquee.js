(function ($) {
    'use strict';

    const REBUILD_DEBOUNCE = 200;
    const IMAGE_WAIT_TIMEOUT = 2500;
    // Runaway guard for content-driven rebuilds only; layout-driven ones (window
    // resizing) must stay unlimited.
    const MAX_CONTENT_REBUILDS = 8;

    let scrollTriggerRefreshTimer = null;

    /**
     * Instances finish building at different times (lazy media, tabs), and every
     * build shifts the elements below it. A single shared refresh makes sure every
     * ScrollTrigger on the page measures against the settled layout.
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

    function destroyBwImageMarquee(wrapper) {
        const api = wrapper._bwImageMarquee;
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

        disconnectObserver(api.visibilityObserver);
        disconnectObserver(api.sizeObserver);

        if (api.track) {
            if (api.originalHtml != null) {
                api.track.innerHTML = api.originalHtml;
            }
            api.track.style.animation = '';
            api.track.style.animationDuration = '';
            api.track.style.animationPlayState = '';
            api.track.style.willChange = '';
            api.track.style.removeProperty('--bw-marquee-shift');
            api.track.classList.remove(
                'vertical',
                'scroll-up',
                'scroll-down',
                'scroll-left',
                'scroll-right'
            );
            if (typeof gsap !== 'undefined') {
                gsap.set(api.track, { clearProps: 'transform,x,y' });
            }
        }

        wrapper.removeAttribute('data-bw-initialized');
        delete wrapper._bwImageMarquee;
    }

    /**
     * Resolves once every image is measurable, or after a grace period. Oversized or
     * lazily deferred images can stay pending for a long time, and blocking on them
     * leaves the marquee frozen with a single un-cloned sequence.
     */
    function waitForImages(root) {
        const pending = Array.prototype.slice.call(root.querySelectorAll('img')).filter(function (img) {
            return !(img.complete && img.naturalWidth);
        });

        if (!pending.length) {
            return Promise.resolve();
        }

        const loaded = Promise.all(
            pending.map(function (img) {
                return new Promise(function (resolve) {
                    img.addEventListener('load', resolve, { once: true });
                    img.addEventListener('error', resolve, { once: true });
                });
            })
        );

        return Promise.race([
            loaded,
            new Promise(function (resolve) {
                setTimeout(resolve, IMAGE_WAIT_TIMEOUT);
            }),
        ]);
    }

    /**
     * Distance between the same edge of both sequences: the exact pixel shift that
     * lands the copy where the original started. Reading the offset of the second
     * sequence rather than the size of the first keeps trailing gaps and sub-pixel
     * widths in, and is unaffected by any transform on the shared parent.
     */
    function measureSeam(first, second, isVertical) {
        const a = first.getBoundingClientRect();
        const b = second.getBoundingClientRect();
        const shift = Math.abs(isVertical ? b.top - a.top : b.left - a.left);
        return shift >= 1 ? shift : (isVertical ? a.height : a.width);
    }

    function buildSeamlessTrack(track, wrapper, isVertical) {
        const originalGroup = track.querySelector('.bw-image-marquee-group');
        if (!originalGroup) {
            return 0;
        }

        const groupTemplate = originalGroup.cloneNode(true);
        track.innerHTML = '';

        const seq = groupTemplate.cloneNode(true);
        seq.classList.add('bw-marquee-seq');
        track.appendChild(seq);

        const wrapSize = isVertical
            ? wrapper.offsetHeight || window.innerHeight
            : wrapper.offsetWidth || window.innerWidth;

        // Measuring one sequence tells us how many copies a cycle needs in a single
        // reflow, and can never stop short of the wrapper the way a capped
        // clone-and-measure loop does.
        const seedItems = Array.prototype.slice.call(seq.children);
        const rect = seq.getBoundingClientRect();
        const unitSize = isVertical ? rect.height : rect.width;

        if (seedItems.length && unitSize >= 1 && unitSize < wrapSize) {
            const extraCopies = Math.min(Math.ceil(wrapSize / unitSize) - 1, 30);
            for (let i = 0; i < extraCopies; i += 1) {
                seedItems.forEach(function (item) {
                    seq.appendChild(item.cloneNode(true));
                });
            }
        }

        const seqClone = seq.cloneNode(true);
        seqClone.setAttribute('aria-hidden', 'true');
        track.appendChild(seqClone);

        return measureSeam(seq, seqClone, isVertical);
    }

    function setupBwImageMarquee(wrapper) {
        const track = wrapper.querySelector('.bw-image-marquee-track');
        if (!track) {
            return;
        }

        destroyBwImageMarquee(wrapper);

        const originalHtml = track.innerHTML;
        if (!track.querySelector('.bw-image-marquee-group')) {
            return;
        }

        // Counted before the loop is built, so the size signature below covers one
        // set of distinct images and stays independent of how many copies a cycle needs.
        const originalItemCount = track.querySelectorAll('.bw-image-marquee-item').length;

        const speedParsed = parseFloat(wrapper.dataset.speed);
        const speed = Number.isFinite(speedParsed) && speedParsed > 0 ? speedParsed : 20;
        const rawDirection = (wrapper.dataset.direction || 'left').toLowerCase();
        const type = wrapper.dataset.type || 'horizontal';
        const useGsap = wrapper.dataset.gsapScroll === 'true';
        const pauseOnHover = wrapper.dataset.pauseHover === 'true';
        const startCondition = wrapper.dataset.gsapStart || 'top bottom';
        const endCondition = wrapper.dataset.gsapEnd || 'bottom top';
        const speedScrollParsed = parseFloat(wrapper.dataset.speedScroll);
        const speedScroll = Number.isFinite(speedScrollParsed) && speedScrollParsed > 0 ? speedScrollParsed : 1;
        const isVertical = type === 'vertical';
        const gapParsed = parseFloat(wrapper.dataset.gap);
        if (Number.isFinite(gapParsed) && gapParsed >= 0) {
            wrapper.style.setProperty('--bw-marquee-gap', gapParsed + 'px');
        }

        let direction;
        if (isVertical) {
            direction = rawDirection === 'down' || rawDirection === 'right' ? 'down' : 'up';
        } else {
            direction = rawDirection === 'right' || rawDirection === 'down' ? 'right' : 'left';
            if (isRtlContext()) {
                direction = direction === 'right' ? 'left' : 'right';
            }
        }

        wrapper.setAttribute('data-bw-initialized', '1');

        // The column layout has to be in place before anything is measured, or the
        // shift is taken from a row layout the animation never uses.
        if (isVertical) {
            track.classList.add('vertical');
        }

        const api = {
            track: track,
            originalHtml: originalHtml,
            builtAtWidth: wrapper.offsetWidth,
            builtAtHeight: wrapper.offsetHeight,
            builtContentSize: '',
            tween: null,
            onEnter: null,
            onLeave: null,
            visibilityObserver: null,
            sizeObserver: null,
            rebuildTimer: null,
            hoverPaused: false,
            offscreenPaused: false,
        };

        function scheduleRebuild(layoutChanged) {
            if (wrapper._bwImageMarquee !== api) {
                return;
            }
            if (!layoutChanged) {
                if ((wrapper._bwContentRebuilds || 0) >= MAX_CONTENT_REBUILDS) {
                    return;
                }
                wrapper._bwContentRebuilds = (wrapper._bwContentRebuilds || 0) + 1;
            }
            clearTimeout(api.rebuildTimer);
            api.rebuildTimer = setTimeout(function () {
                if (wrapper._bwImageMarquee !== api) {
                    return;
                }
                setupBwImageMarquee(wrapper);
            }, REBUILD_DEBOUNCE);
        }

        function syncCssPlayState() {
            if (!api.track || api.tween) {
                return;
            }
            const paused = api.hoverPaused || api.offscreenPaused;
            api.track.style.animationPlayState = paused ? 'paused' : 'running';
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
            // ScrollTrigger already idles outside its own range; disabling it here
            // froze the track at a stale transform whenever the marquee was scrolled
            // past, so only the CSS keyframe loop gets an offscreen pause.
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

        function contentUnits() {
            const group = track.querySelector('.bw-image-marquee-group');
            if (!group) {
                return [];
            }
            return Array.prototype.slice
                .call(group.querySelectorAll('.bw-image-marquee-item'))
                .slice(0, originalItemCount);
        }

        /**
         * Sub-pixel size of one set of distinct items. Ancestor transforms only
         * translate, so this stays a pure layout measurement.
         */
        function contentSize() {
            return contentUnits()
                .map(function (item) {
                    const rect = item.getBoundingClientRect();
                    return Math.round(rect.width * 100) + 'x' + Math.round(rect.height * 100);
                })
                .join(',');
        }

        /**
         * Rebuild when the available space changes - including 0 -> visible, which is
         * how the widget recovers inside tabs, accordions and off-canvas panels - and
         * when the items themselves resize. An image finishing after the grace period,
         * or a responsive image size, changes the loop length while the wrapper stays
         * exactly as big as before.
         */
        function observeSize() {
            if (typeof ResizeObserver === 'undefined') {
                return;
            }
            api.sizeObserver = new ResizeObserver(function () {
                if (wrapper._bwImageMarquee !== api) {
                    return;
                }
                const layoutChanged =
                    wrapper.offsetWidth !== api.builtAtWidth || wrapper.offsetHeight !== api.builtAtHeight;
                if (!layoutChanged && contentSize() === api.builtContentSize) {
                    return;
                }
                scheduleRebuild(layoutChanged);
            });

            api.sizeObserver.observe(wrapper);
            contentUnits().forEach(function (unit) {
                api.sizeObserver.observe(unit);
            });
        }

        function startAnimation(shift) {
            if (!(shift >= 1)) {
                return;
            }

            track.style.setProperty('--bw-marquee-shift', shift + 'px');

            if (useGsap && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
                track.style.animation = 'none';

                const scrollProp = isVertical ? 'y' : 'x';
                // Match CSS class directions / legacy Elementor labels.
                const fromVal =
                    direction === 'left' || direction === 'down'
                        ? -shift
                        : 0;
                const toVal =
                    direction === 'left' || direction === 'down'
                        ? 0
                        : -shift;

                track.style.willChange = 'transform';

                api.tween = gsap.fromTo(
                    track,
                    { [scrollProp]: fromVal },
                    {
                        [scrollProp]: toVal,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: wrapper,
                            start: startCondition,
                            end: endCondition,
                            scrub: speedScroll,
                            invalidateOnRefresh: true,
                        },
                    }
                );

                scheduleScrollTriggerRefresh();
            } else {
                if (isVertical) {
                    track.classList.add(direction === 'down' ? 'scroll-down' : 'scroll-up');
                } else {
                    track.classList.add(direction === 'right' ? 'scroll-right' : 'scroll-left');
                }
                // Duration is full-cycle seconds (Elementor control); keep that UX.
                track.style.animationDuration = speed + 's';
            }

            attachPauseOnHover();
            attachVisibilityPause();
        }

        if (prefersReducedMotion()) {
            wrapper._bwImageMarquee = api;
            return;
        }

        wrapper._bwImageMarquee = api;

        waitForImages(track).then(function () {
            // Widget may have been destroyed/re-inited while images loaded.
            if (wrapper._bwImageMarquee !== api) {
                return;
            }
            startAnimation(buildSeamlessTrack(track, wrapper, isVertical));
            // Observed after the build, since the items it watches are created by it.
            api.builtAtWidth = wrapper.offsetWidth;
            api.builtAtHeight = wrapper.offsetHeight;
            api.builtContentSize = contentSize();
            observeSize();
        });
    }

    function initBwImageMarquee($scope) {
        $scope.find('.bw-image-marquee-wrapper').each(function () {
            setupBwImageMarquee(this);
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/b_image_marquee.default',
            initBwImageMarquee
        );
    });
})(jQuery);
