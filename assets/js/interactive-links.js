(function ($) {
        'use strict';

        const initBWInteractiveWidget = function ($scope) {

            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                return;
            }

            gsap.registerPlugin(ScrollTrigger);

            const $containerBox = $scope.find('.bw-interactive-link-box');
            const gsapSettings = $containerBox.length ? JSON.parse($containerBox.attr('data-gsap-settings')) : {};

            const $image = $scope.find('.bw-il-hover-image');
            const $wrapper = $scope.find('.bw-il-hover-wrapper');
            const $container = $scope.find('.bw-il-image-container');
            const $menuItems = $scope.find('.bw-il-menu li');

            if (!$wrapper.length || !$image.length || !$menuItems.length) {
                return;
            }

            const mouse = { x: 0, y: 0 };
            const lastMouse = { x: 0, y: 0 };
            const direction = { x: 0, y: 0 };

            function updateWrapperPosition() {
                const wrapperW = $wrapper.outerWidth();
                const wrapperH = $wrapper.outerHeight();

                direction.x = mouse.x - lastMouse.x;
                direction.y = mouse.y - lastMouse.y;
                lastMouse.x = mouse.x;
                lastMouse.y = mouse.y;

                const relX = (mouse.x / window.innerWidth - 0.5) * 2;
                const relY = (mouse.y / window.innerHeight - 0.5) * 2;

                const rotX = gsap.utils.clamp(-gsapSettings.maxRotX, gsapSettings.maxRotX, -relY * gsapSettings.maxRotX);
                const rotY = gsap.utils.clamp(-gsapSettings.maxRotY, gsapSettings.maxRotY, relX * gsapSettings.maxRotY);
                const transX = gsap.utils.clamp(-gsapSettings.maxTransX, gsapSettings.maxTransX, relX * gsapSettings.maxTransX);
                const transY = gsap.utils.clamp(-gsapSettings.maxTransY, gsapSettings.maxTransY, relY * gsapSettings.maxTransY);

                const brightness = 1 + Math.min(Math.abs(direction.x) + Math.abs(direction.y), 100) / 100 * gsapSettings.brightnessStrength;

                gsap.to($wrapper, {
                    x: mouse.x - wrapperW / 2,
                    y: mouse.y - wrapperH / 2,
                    rotationX: rotX,
                    rotationY: rotY,
                    rotationZ: direction.x * gsapSettings.rotZStrength,
                    filter: `brightness(${brightness})`,
                    ease: gsapSettings.ease,
                    duration: gsapSettings.duration,
                    transformOrigin: "center center",
                });
            }

            function render() {
                updateWrapperPosition();
                requestAnimationFrame(render);
            }

            render();

            $menuItems.on('mouseenter', function () {
                gsap.to($wrapper, {
                    autoAlpha: 1,
                    duration: 0.2,
                    ease: "power2.out"
                });

                const imgSrc = $(this).data('image');
                if (imgSrc) {
                    $image.attr('src', imgSrc);
                    gsap.to($image, { opacity: 1, duration: gsapSettings.fadeIn });
                }
            });

            $menuItems.on('mouseleave', function () {
                gsap.to($wrapper, {
                    autoAlpha: 0,
                    duration: 0.2,
                    ease: "power2.in"
                });
                gsap.to($image, { opacity: 0, duration: gsapSettings.fadeOut });
            });

            $(window).on('mousemove', function (e) {
                mouse.x = e.clientX;
                mouse.y = e.clientY;
            });
        };

        jQuery(document).ready(function($) {
            initBWInteractiveWidget($(document));
        });

        $(window).on('elementor/frontend/init', function () {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/b_gsap_interactive_links.default',
                function($scope) {
                    initBWInteractiveWidget($scope);
                }
            );
        });

})(jQuery);
