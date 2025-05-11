(function ($) {
    $('.black-tab--default').each(function () {
        const $tabWidget = $(this);
        const $tabs = $tabWidget.find('.black-tab__tab');
        const $mediaItems = $tabWidget.find('.black-tab__media');
        const $timelineFills = $tabWidget.find('.black-tab__timeline-fill');

        const autoplayDelay = $tabWidget.data('delay') ? parseInt($tabWidget.data('delay')) : false;
        let currentIndex = 0;
        let autoplayTimer;

        function activateTab(index) {
                $tabs.each(function (i) {
                $(this).toggleClass('black-tab__tab--active', i === index);
            });

            $timelineFills.each(function (i) {
                const $fill = $(this);
                gsap.killTweensOf($fill[0]);
                gsap.set($fill[0], { height: '0%' });

                if (i === index && autoplayDelay) {
                    gsap.to($fill[0], {
                        height: '100%',
                        duration: autoplayDelay / 1000,
                        ease: 'linear'
                    });
                }
            });

            $mediaItems.each(function (i) {
                const $media = $(this);
                const isActive = i === index;
                if (isActive) {
                    gsap.set($media[0], { x: 100, opacity: 0, height: 'auto', display: 'block' });
                    gsap.fromTo($media[0],
                        { x: 100, opacity: 0 },
                        { x: 0, opacity: 1, duration: 1.3, ease: 'power2.out' }
                    );
                } else {
                    gsap.set($media[0], { x: 100, opacity: 0, height: '0', display: 'none' });
                }
            });

            $tabs.each(function (i) {
                const $desc = $(this).find('.black-tab__tab-description');
                if (!$desc.length) return;

                gsap.killTweensOf($desc[0]);

                if (i === index) {
                    gsap.fromTo($desc[0],
                        { height: 0, opacity: 0 },
                        {
                            height: 'auto', opacity: 1,
                            duration: 0.5, ease: "power2.out"
                        }
                    );
                } else {
                    gsap.to($desc[0],
                        { height: 0, opacity: 0, duration: 0.5, ease: "power2.in" }
                    );
                }
            });

            currentIndex = index;
        }

        function nextTab() {
            const nextIndex = (currentIndex + 1) % $tabs.length;
            activateTab(nextIndex);
        }

        function startAutoplay() {
            autoplayTimer = setInterval(nextTab, autoplayDelay);
        }

        function stopAutoplay() {
            clearInterval(autoplayTimer);
            $timelineFills.each(function (i) {
                const $fill = $(this);
                gsap.killTweensOf($fill[0]);
                gsap.set($fill[0], { height: '0%' });
            });
        }

        $tabs.each(function (i) {
            $(this).on('click', function () {
                stopAutoplay();
                activateTab(i);
                if (autoplayDelay) startAutoplay();
            });
        });

        activateTab(currentIndex);
        if (autoplayDelay) startAutoplay();
    });

    const BlackTab = function($scope, $) {
        const $widget = $scope.find('.black-tab--vertical');

        if ($widget.length === 0) return;

        const $tabs = $widget.find('.black-tab__tab');
        const $panels = $widget.find('.black-tab__panel');

        const autoplayDelay = $widget.data('delay') ? parseInt($widget.data('delay')) : false;

        if (!$tabs.length || !$panels.length) return;

        let activeIndex = 0;
        let intervalId = null;

        const showTab = (index) => {
            const $tab = $tabs.eq(index);
            const $panel = $panels.eq(index);

            if ($tab.hasClass('black-tab__tab--active')) return;

            $tabs.removeClass('black-tab__tab--active');
            $panels.removeClass('black-tab__panel--active');

            $tab.addClass('black-tab__tab--active');
            $panel.addClass('black-tab__panel--active');

            gsap.fromTo($panel,
                { opacity: 0, y: 20 },
                { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }
            );

            activeIndex = index;
        };

        const nextTab = () => {
            const nextIndex = (activeIndex + 1) % $tabs.length;
            showTab(nextIndex);
        };

        $tabs.on('click', function() {
            const index = $(this).index();
            showTab(index);
            if (autoplayDelay) {
                startAutoplay();
            }
        });

        function startAutoplay() {
            stopAutoplay();
            intervalId = setInterval(nextTab, autoplayDelay);
        }

        function stopAutoplay() {
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }
        }

        showTab(activeIndex);
        if (autoplayDelay) {
            startAutoplay();
        }
    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/b_gsap_tab.default', BlackTab);
    });
})(jQuery);
