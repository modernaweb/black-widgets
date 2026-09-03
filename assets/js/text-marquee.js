(function ($) {
    'use strict';

    function prefersReducedMotion() {
        return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
    }

    function isRtlContext() {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.config && typeof elementorFrontend.config.is_rtl !== 'undefined') {
            return !!elementorFrontend.config.is_rtl;
        }
        return document.documentElement.getAttribute('dir') === 'rtl' || document.body.classList.contains('rtl');
    }

    function destroyBwTextMarquee(wrapper) {
        const api = wrapper._bwTextMarquee;
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

        if (api.styleEl && api.styleEl.parentNode) {
            api.styleEl.parentNode.removeChild(api.styleEl);
        }

        if (api.visibilityObserver) {
            try {
                api.visibilityObserver.disconnect();
            } catch (e) {
                // ignore
            }
        }

        if (api.inner && api.originalInnerHtml != null) {
            api.inner.innerHTML = api.originalInnerHtml;
            api.inner.style.animation = '';
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
     * Build two identical sequences. Trailing gap is on items (CSS), not between
     * halves - so pixel shift === firstSeq width and the loop never jumps.
     */
    function buildSeamlessSequences(inner, template, minWidth) {
        const seq = document.createElement('div');
        seq.className = 'bw-marquee-seq';
        seq.appendChild(template.cloneNode(true));

        inner.innerHTML = '';
        inner.appendChild(seq);

        // Fill first sequence until it covers the viewport (or more).
        let guard = 0;
        while (seq.scrollWidth < minWidth && guard < 40) {
            seq.appendChild(template.cloneNode(true));
            guard += 1;
        }

        // Always at least one full cycle worth of content.
        if (seq.childElementCount < 1) {
            seq.appendChild(template.cloneNode(true));
        }

        const seqClone = seq.cloneNode(true);
        seqClone.setAttribute('aria-hidden', 'true');
        inner.appendChild(seqClone);

        return seq.offsetWidth || seq.scrollWidth || 1;
    }

    function initBwTextMarquee($scope) {
        const $wrappers = $scope.find('.bw-text-marquee-wrapper');

        $wrappers.each(function () {
            const wrapper = this;
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
            const endCondition = settings.gsapEnd || 'bottom top';
            const speedScroll = parseFloat(settings.speedScroll);
            const resolvedSpeedScroll = Number.isFinite(speedScroll) && speedScroll > 0 ? speedScroll : 2;
            const gap = parseFloat(settings.gap);
            const resolvedGap = Number.isFinite(gap) && gap >= 0 ? gap : 0;
            const pauseOnHover = wrapper.classList.contains('pause-on-hover');

            wrapper.style.setProperty('--bw-marquee-gap', resolvedGap + 'px');

            if (isRtlContext()) {
                direction = direction === 'right' ? 'left' : 'right';
            }

            const api = {
                inner: inner,
                originalInnerHtml: originalInnerHtml,
                tween: null,
                styleEl: null,
                visibilityObserver: null,
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

            function syncGsapPlayState() {
                if (!api.tween) {
                    return;
                }
                if (api.hoverPaused || api.offscreenPaused) {
                    api.tween.pause();
                } else {
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
                                } else {
                                    api.tween.scrollTrigger.disable(false);
                                }
                                return;
                            }
                            if (api.tween) {
                                syncGsapPlayState();
                                return;
                            }
                            syncCssPlayState();
                        });
                    },
                    { root: null, rootMargin: '50px 0px', threshold: 0 }
                );
                api.visibilityObserver.observe(wrapper);
            }

            function applyPixelLoop(shift) {
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

                const minWidth = Math.max(wrapper.offsetWidth || 0, window.innerWidth || 0);

                if (scrollControlled) {
                    inner.style.animation = 'none';
                    const shift = buildSeamlessSequences(inner, template, minWidth);

                    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                        gsap.registerPlugin(ScrollTrigger);
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

                        if (api.tween.scrollTrigger) {
                            api.tween.scrollTrigger.refresh();
                        }
                    }

                    attachPauseOnHover();
                    attachVisibilityPause();
                    wrapper._bwTextMarquee = api;
                    return;
                }

                const shift = buildSeamlessSequences(inner, template, minWidth * 1.25);
                applyPixelLoop(shift);
            } else if (inner.classList.contains('bw-marquee-inner-single')) {
                const wrapperWidth = wrapper.offsetWidth || window.innerWidth;
                const temp = inner.cloneNode(true);
                Object.assign(temp.style, {
                    position: 'absolute',
                    visibility: 'hidden',
                    whiteSpace: 'nowrap',
                    left: '-9999px',
                    top: '0',
                });
                document.body.appendChild(temp);
                const textWidth = temp.offsetWidth || 1;
                document.body.removeChild(temp);

                const start = direction === 'right' ? wrapperWidth : -textWidth;
                const end = direction === 'right' ? -textWidth : wrapperWidth;
                const distance = Math.abs(end - start) || 1;
                const duration = distance / resolvedSpeed;
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

            attachPauseOnHover();
            attachVisibilityPause();
            wrapper._bwTextMarquee = api;
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/b_text_marquee.default',
            initBwTextMarquee
        );
    });
})(jQuery);
