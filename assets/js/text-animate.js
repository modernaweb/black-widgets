(function ($) {
    'use strict';

    const API_KEY = 'bwTextAnimateApi';

    function isElementorEditor() {
        return !!(
            typeof elementorFrontend !== 'undefined' &&
            elementorFrontend.isEditMode &&
            elementorFrontend.isEditMode()
        );
    }

    function prefersReducedMotion() {
        return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
    }

    function playBwTextAnimate($el) {
        if (prefersReducedMotion() || typeof anime === 'undefined') {
            $el.find('.bw-text-animate-content').css({ opacity: 1, transform: 'none' });
            $el.attr('data-bw-animated', '1');
            return;
        }

        const animation = $el.data('animation') || 'ftop';
        const delay = parseInt($el.data('delay'), 10) || 500;
        const timeline = anime.timeline({ easing: 'easeOutExpo', duration: delay });

        $el.find('.bw-text-animate-content').each(function () {
            timeline.add({
                targets: this,
                translateY:
                    animation === 'ftop' ? ['-100%', 0] :
                    animation === 'fbottom' ? ['100%', 0] :
                    undefined,
                translateX:
                    animation === 'fleft' ? [-100, 0] :
                    animation === 'fright' ? [100, 0] :
                    undefined,
                opacity: [0, 1],
            });
        });

        $el.attr('data-bw-animated', '1');
    }

    function resetBwTextAnimate($el) {
        $el.removeAttr('data-bw-animated');
        $el.find('.bw-text-animate-content').each(function () {
            this.style.opacity = '';
            this.style.transform = '';
            if (typeof anime !== 'undefined') {
                anime.remove(this);
            }
        });
    }

    function getHscrollPanel(el) {
        const wrap = el.closest('.bw-horizontal-section');
        if (!wrap || wrap.classList.contains('bw-hscroll-stacked') || wrap.classList.contains('bw-hscroll-editor')) {
            return null;
        }
        return el.closest('.bw-hscroll-track > .elementor') || el.closest('.bw-section > .elementor') || el.closest('.elementor');
    }

    function isInsideInactiveHscroll(el) {
        const panel = getHscrollPanel(el);
        return !!(panel && !panel.classList.contains('bw-hscroll-active'));
    }

    function destroyBwTextAnimate($scope) {
        const api = $scope.data(API_KEY);
        if (!api) {
            return;
        }
        if (api.observer) {
            api.observer.disconnect();
        }
        if (api.enterCleanups) {
            api.enterCleanups.forEach(function (fn) {
                try {
                    fn();
                } catch (e) {
                    // no-op
                }
            });
        }
        $scope.removeData(API_KEY);
    }

    function bwTextAnimateInit($scope) {
        destroyBwTextAnimate($scope);

        const $targetElements = $scope.find('.bw-text-animate');
        if (!$targetElements.length) {
            return;
        }

        $targetElements.each(function () {
            resetBwTextAnimate($(this));
        });

        if (isElementorEditor()) {
            $targetElements.each(function () {
                playBwTextAnimate($(this));
            });
            return;
        }

        const observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }
                if (isInsideInactiveHscroll(entry.target)) {
                    return;
                }
                const $el = $(entry.target);
                playBwTextAnimate($el);
                obs.unobserve(entry.target);
            });
        }, {
            threshold: 0.4,
        });

        const enterCleanups = [];
        $targetElements.each(function () {
            observer.observe(this);
            const panel = getHscrollPanel(this);
            if (!panel) {
                return;
            }
            const el = this;
            const onEnter = function () {
                if (el.getAttribute('data-bw-animated') === '1') {
                    return;
                }
                playBwTextAnimate($(el));
            };
            panel.addEventListener('bw-hscroll-panel-enter', onEnter);
            enterCleanups.push(function () {
                panel.removeEventListener('bw-hscroll-panel-enter', onEnter);
            });
        });

        $scope.data(API_KEY, { observer: observer, enterCleanups: enterCleanups });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/b_text_animate.default',
            function ($scope) {
                bwTextAnimateInit($scope);
            }
        );
    });
})(jQuery);