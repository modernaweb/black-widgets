(function ($) {
    'use strict';

    const API_KEY = 'bwTabApi';

    function parseAutoplayDelay(raw) {
        if (raw === undefined || raw === null || raw === '' || raw === false) {
            return false;
        }
        const delay = parseInt(raw, 10);
        if (!delay || isNaN(delay)) {
            return false;
        }
        return delay < 100 ? 6000 : delay;
    }

    function prefersReducedMotion() {
        return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
    }

    function destroyTabApi($el) {
        const api = $el.data(API_KEY);
        if (!api || typeof api.destroy !== 'function') {
            return;
        }
        api.destroy();
        $el.removeData(API_KEY);
    }

    function syncTabAria($tabs, $panels, activeIndex) {
        $tabs.each(function (i) {
            const isActive = i === activeIndex;
            $(this)
                .attr('aria-selected', isActive ? 'true' : 'false')
                .attr('tabindex', isActive ? '0' : '-1')
                .toggleClass('black-tab__tab--active', isActive);
        });

        $panels.each(function (i) {
            const isActive = i === activeIndex;
            const $panel = $(this);
            $panel.toggleClass('black-tab__panel--active', isActive);
            if (isActive) {
                $panel.removeAttr('hidden');
            } else {
                $panel.attr('hidden', 'hidden');
            }
        });
    }

    function bindTabKeyboard($tabs, activateFn) {
        $tabs.on('keydown.bwTab', function (e) {
            const count = $tabs.length;
            const current = $tabs.index(this);
            let next = -1;

            switch (e.key) {
                case 'ArrowRight':
                case 'ArrowDown':
                    next = (current + 1) % count;
                    break;
                case 'ArrowLeft':
                case 'ArrowUp':
                    next = (current - 1 + count) % count;
                    break;
                case 'Home':
                    next = 0;
                    break;
                case 'End':
                    next = count - 1;
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    activateFn(current, true);
                    return;
                default:
                    return;
            }

            e.preventDefault();
            activateFn(next, true);
            $tabs.eq(next).trigger('focus');
        });
    }

    const BlackTabDefault = function ($scope) {
        if (typeof gsap === 'undefined') {
            return;
        }

        $scope.find('.black-tab--default').each(function () {
            const $tabWidget = $(this);
            const $tabs = $tabWidget.find('.black-tab__tab');
            const $mediaItems = $tabWidget.find('.black-tab__media');
            const $timelineFills = $tabWidget.find('.black-tab__timeline-fill');
            const reduceMotion = prefersReducedMotion();

            destroyTabApi($tabWidget);

            const autoplayDelay = parseAutoplayDelay($tabWidget.data('delay'));
            let currentIndex = 0;
            let autoplayTimer = null;
            const mediaFromX = (typeof elementorFrontend !== 'undefined' && elementorFrontend.config && elementorFrontend.config.is_rtl) ? -100 : 100;

            function activateTab(index, fromUser) {
                syncTabAria($tabs, $mediaItems, index);

                $tabs.each(function (i) {
                    $(this).find('.black-tab__tab-title').toggleClass('black-tab__tab-title--active', i === index);
                });

                $timelineFills.each(function (i) {
                    const $fill = $(this);
                    gsap.killTweensOf($fill[0]);
                    gsap.set($fill[0], { height: '0%' });

                    if (i === index && autoplayDelay && !reduceMotion) {
                        gsap.to($fill[0], {
                            height: '100%',
                            duration: autoplayDelay / 1000,
                            ease: 'linear',
                        });
                    } else if (i === index && autoplayDelay && reduceMotion) {
                        gsap.set($fill[0], { height: '100%' });
                    }
                });

                $mediaItems.each(function (i) {
                    const $media = $(this);
                    const isActive = i === index;
                    if (isActive) {
                        if (reduceMotion) {
                            gsap.set($media[0], { x: 0, opacity: 1, height: 'auto', display: 'block' });
                        } else {
                            gsap.set($media[0], { x: mediaFromX, opacity: 0, height: 'auto', display: 'block' });
                            gsap.fromTo(
                                $media[0],
                                { x: mediaFromX, opacity: 0 },
                                { x: 0, opacity: 1, duration: 1.3, ease: 'power2.out' }
                            );
                        }
                    } else {
                        gsap.set($media[0], { x: mediaFromX, opacity: 0, height: '0', display: 'none' });
                    }
                });

                $tabs.each(function (i) {
                    const $desc = $(this).find('.black-tab__tab-description');
                    if (!$desc.length) {
                        return;
                    }

                    gsap.killTweensOf($desc[0]);

                    if (i === index) {
                        if (reduceMotion) {
                            gsap.set($desc[0], { height: 'auto', opacity: 1 });
                        } else {
                            gsap.fromTo(
                                $desc[0],
                                { height: 0, opacity: 0 },
                                {
                                    height: 'auto',
                                    opacity: 1,
                                    duration: 0.5,
                                    ease: 'power2.out',
                                }
                            );
                        }
                    } else if (reduceMotion) {
                        gsap.set($desc[0], { height: 0, opacity: 0 });
                    } else {
                        gsap.to($desc[0], {
                            height: 0,
                            opacity: 0,
                            duration: 0.5,
                            ease: 'power2.in',
                        });
                    }
                });

                currentIndex = index;

                if (fromUser && autoplayDelay) {
                    startAutoplay();
                }
            }

            function nextTab() {
                activateTab((currentIndex + 1) % $tabs.length, false);
            }

            let inView = true;

            function clearAutoplayTimer() {
                if (autoplayTimer) {
                    clearInterval(autoplayTimer);
                    autoplayTimer = null;
                }
            }

            function startAutoplay() {
                clearAutoplayTimer();
                if (!autoplayDelay || !inView) {
                    return;
                }
                autoplayTimer = setInterval(nextTab, autoplayDelay);
            }

            function stopAutoplay() {
                clearAutoplayTimer();
                $timelineFills.each(function () {
                    const $fill = $(this);
                    gsap.killTweensOf($fill[0]);
                    gsap.set($fill[0], { height: '0%' });
                });
            }

            function onTabClick() {
                const index = $tabs.index(this);
                stopAutoplay();
                activateTab(index, true);
            }

            $tabs.on('click.bwTab', onTabClick);
            bindTabKeyboard($tabs, function (index, fromUser) {
                stopAutoplay();
                activateTab(index, fromUser);
            });

            let viewportObserver = null;
            if (autoplayDelay && typeof IntersectionObserver !== 'undefined') {
                viewportObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        inView = entry.isIntersecting;
                        if (inView) {
                            startAutoplay();
                        } else {
                            clearAutoplayTimer();
                        }
                    });
                }, { threshold: 0.15 });
                viewportObserver.observe($tabWidget[0]);
            }

            $tabWidget.data(API_KEY, {
                destroy: function () {
                    stopAutoplay();
                    if (viewportObserver) {
                        viewportObserver.disconnect();
                    }
                    $tabs.off('click.bwTab keydown.bwTab');
                    $timelineFills.each(function () {
                        gsap.killTweensOf(this);
                    });
                    $mediaItems.each(function () {
                        gsap.killTweensOf(this);
                    });
                    $tabs.find('.black-tab__tab-description').each(function () {
                        gsap.killTweensOf(this);
                    });
                },
            });

            activateTab(currentIndex, false);
            if (autoplayDelay) {
                startAutoplay();
            }
        });
    };

    const BlackTabVertical = function ($scope) {
        if (typeof gsap === 'undefined') {
            return;
        }

        $scope.find('.black-tab--vertical').each(function () {
            const $widget = $(this);
            const $tabs = $widget.find('.black-tab__tab');
            const $panels = $widget.find('.black-tab__panel');
            const reduceMotion = prefersReducedMotion();

            destroyTabApi($widget);

            const autoplayDelay = parseAutoplayDelay($widget.data('delay'));

            if (!$tabs.length || !$panels.length) {
                return;
            }

            let activeIndex = 0;
            let intervalId = null;

            const showTab = function (index, fromUser) {
                const $tab = $tabs.eq(index);
                const $panel = $panels.eq(index);

                if ($tab.hasClass('black-tab__tab--active') && index === activeIndex) {
                    return;
                }

                syncTabAria($tabs, $panels, index);

                if (reduceMotion) {
                    gsap.set($panel, { opacity: 1, y: 0 });
                } else {
                    gsap.fromTo(
                        $panel,
                        { opacity: 0, y: 20 },
                        { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }
                    );
                }

                activeIndex = index;

                if (fromUser && autoplayDelay) {
                    startAutoplay();
                }
            };

            const nextTab = function () {
                showTab((activeIndex + 1) % $tabs.length, false);
            };

            let inView = true;

            function clearAutoplayTimer() {
                if (intervalId) {
                    clearInterval(intervalId);
                    intervalId = null;
                }
            }

            function stopAutoplay() {
                clearAutoplayTimer();
            }

            function startAutoplay() {
                clearAutoplayTimer();
                if (!autoplayDelay || !inView) {
                    return;
                }
                intervalId = setInterval(nextTab, autoplayDelay);
            }

            function onTabClick() {
                // Use tab collection index - not sibling index (non-tab siblings break .index()).
                const index = $tabs.index(this);
                showTab(index, true);
            }

            $tabs.on('click.bwTab', onTabClick);
            bindTabKeyboard($tabs, function (index, fromUser) {
                showTab(index, fromUser);
            });

            let viewportObserver = null;
            if (autoplayDelay && typeof IntersectionObserver !== 'undefined') {
                viewportObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        inView = entry.isIntersecting;
                        if (inView) {
                            startAutoplay();
                        } else {
                            clearAutoplayTimer();
                        }
                    });
                }, { threshold: 0.15 });
                viewportObserver.observe($widget[0]);
            }

            $widget.data(API_KEY, {
                destroy: function () {
                    stopAutoplay();
                    if (viewportObserver) {
                        viewportObserver.disconnect();
                    }
                    $tabs.off('click.bwTab keydown.bwTab');
                    $panels.each(function () {
                        gsap.killTweensOf(this);
                    });
                },
            });

            showTab(activeIndex, false);
            if (autoplayDelay) {
                startAutoplay();
            }
        });
    };

    // Always register hooks - GSAP may be missing; handlers no-op safely.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/b_gsap_tab.default', BlackTabDefault);
        elementorFrontend.hooks.addAction('frontend/element_ready/b_gsap_tab.default', BlackTabVertical);
    });
})(jQuery);
