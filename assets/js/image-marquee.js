(function ($) {
    'use strict';

    function destroyBwImageMarquee(wrapper) {
        const api = wrapper._bwImageMarquee;
        if (!api) {
            return;
        }

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

        if (api.visibilityObserver) {
            try {
                api.visibilityObserver.disconnect();
            } catch (e) {
                // ignore
            }
        }

        if (api.track) {
            if (api.originalHtml != null) {
                api.track.innerHTML = api.originalHtml;
            }
            api.track.style.animation = '';
            api.track.style.animationDuration = '';
            api.track.style.willChange = '';
            api.track.style.removeProperty('--bw-marquee-shift');
            api.track.classList.remove(
                'vertical',
                'scroll-up',
                'scroll-down',
                'scroll-left',
                'scroll-right'
            );
        }

        wrapper.removeAttribute('data-bw-initialized');
        delete wrapper._bwImageMarquee;
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

    function waitForImages(root) {
        const imgs = Array.prototype.slice.call(root.querySelectorAll('img'));
        if (!imgs.length) {
            return Promise.resolve();
        }
        return Promise.all(
            imgs.map(function (img) {
                if (img.complete && img.naturalWidth) {
                    return Promise.resolve();
                }
                return new Promise(function (resolve) {
                    const done = function () {
                        resolve();
                    };
                    img.addEventListener('load', done, { once: true });
                    img.addEventListener('error', done, { once: true });
                });
            })
        );
    }

    /**
     * Two identical halves. Gap is trailing margin on items (CSS), not between
     * groups - so shift === first half size and the loop never jumps.
     */
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

        const measure = function () {
            return isVertical ? seq.scrollHeight : seq.scrollWidth;
        };

        // If a single group is shorter than the viewport, duplicate its items
        // inside the sequence until it covers the view (common with few logos).
        let guard = 0;
        const seedItems = Array.prototype.slice.call(seq.children);
        while (measure() < wrapSize && seedItems.length && guard < 30) {
            seedItems.forEach(function (item) {
                seq.appendChild(item.cloneNode(true));
            });
            guard += 1;
        }

        const halfSize = measure() || 1;

        const seqClone = seq.cloneNode(true);
        seqClone.setAttribute('aria-hidden', 'true');
        track.appendChild(seqClone);

        return halfSize;
    }

    function initBwImageMarquee($scope) {
        const $wrappers = $scope.find('.bw-image-marquee-wrapper');

        $wrappers.each(function () {
            const wrapper = this;
            const track = wrapper.querySelector('.bw-image-marquee-track');

            if (!track) {
                return;
            }

            destroyBwImageMarquee(wrapper);

            const originalHtml = track.innerHTML;
            if (!track.querySelector('.bw-image-marquee-group')) {
                return;
            }

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

            const api = {
                track: track,
                originalHtml: originalHtml,
                tween: null,
                onEnter: null,
                onLeave: null,
                visibilityObserver: null,
                hoverPaused: false,
                offscreenPaused: false,
            };

            function syncCssPlayState() {
                if (!api.track || api.tween) {
                    return;
                }
                const paused = api.hoverPaused || api.offscreenPaused;
                api.track.style.animationPlayState = paused ? 'paused' : 'running';
            }

            function syncGsapPlayState() {
                if (!api.tween) {
                    return;
                }
                if (api.hoverPaused) {
                    api.tween.pause();
                } else if (!api.offscreenPaused) {
                    api.tween.resume();
                }
            }

            function attachPauseOnHover() {
                if (!pauseOnHover) {
                    return;
                }
                api.onEnter = function () {
                    api.hoverPaused = true;
                    if (api.tween) {
                        syncGsapPlayState();
                    } else {
                        syncCssPlayState();
                    }
                };
                api.onLeave = function () {
                    api.hoverPaused = false;
                    if (api.tween) {
                        syncGsapPlayState();
                    } else {
                        syncCssPlayState();
                    }
                };
                wrapper.addEventListener('mouseenter', api.onEnter);
                wrapper.addEventListener('mouseleave', api.onLeave);
            }

            function attachVisibilityPause() {
                if (typeof IntersectionObserver === 'undefined') {
                    return;
                }
                api.visibilityObserver = new IntersectionObserver(
                    function (entries) {
                        entries.forEach(function (entry) {
                            api.offscreenPaused = !entry.isIntersecting;
                            if (api.tween && api.tween.scrollTrigger) {
                                if (entry.isIntersecting) {
                                    api.tween.scrollTrigger.enable();
                                    if (!api.hoverPaused) {
                                        api.tween.resume();
                                    }
                                } else {
                                    api.tween.scrollTrigger.disable(false);
                                }
                                return;
                            }
                            syncCssPlayState();
                        });
                    },
                    { root: null, rootMargin: '50px 0px', threshold: 0 }
                );
                api.visibilityObserver.observe(wrapper);
            }

            function startAnimation(shift) {
                if (!shift || shift < 1) {
                    return;
                }

                track.style.setProperty('--bw-marquee-shift', shift + 'px');

                if (useGsap && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                    gsap.registerPlugin(ScrollTrigger);
                    track.style.animation = 'none';
                    if (isVertical) {
                        track.classList.add('vertical');
                    }

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
                } else {
                    if (isVertical) {
                        track.classList.add('vertical', direction === 'down' ? 'scroll-down' : 'scroll-up');
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
                const shift = buildSeamlessTrack(track, wrapper, isVertical);
                startAnimation(shift);
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/b_image_marquee.default',
            initBwImageMarquee
        );
    });
})(jQuery);
