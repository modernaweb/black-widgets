(function ($) {
    'use strict';

    const NS = 'bwInteractiveLinks';

    function prefersReducedMotion() {
        return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
    }

    function isCoarsePointer() {
        return !!(
            window.matchMedia &&
            window.matchMedia('(hover: none), (pointer: coarse)').matches
        );
    }

    function destroyBWInteractiveWidget($scope) {
        const api = $scope.data(NS);
        if (!api) {
            return;
        }

        api.running = false;
        api.active = false;

        if (api.rafId) {
            cancelAnimationFrame(api.rafId);
            api.rafId = 0;
        }

        $(window).off('mousemove.' + api.ns);
        api.$menuItems.off('.' + api.ns);

        if (typeof gsap !== 'undefined') {
            gsap.killTweensOf(api.$wrapper);
            gsap.killTweensOf(api.$image);
        }

        // Restore hover layer to original parent if it was portaled to body.
        if (api.$imageContainer && api.$imageContainer.length && api.$homeParent && api.$homeParent.length) {
            api.$imageContainer.appendTo(api.$homeParent);
        }

        $scope.removeData(NS);
        $scope.removeAttr('data-bw-il-ready');
    }

    function initBWInteractiveWidget($scope) {
        if (typeof gsap === 'undefined') {
            return;
        }

        destroyBWInteractiveWidget($scope);

        const $containerBox = $scope.find('.bw-interactive-link-box');
        let gsapSettings = {};

        if ($containerBox.length) {
            try {
                gsapSettings = JSON.parse($containerBox.attr('data-gsap-settings') || '{}') || {};
            } catch (e) {
                gsapSettings = {};
            }
        }

        const $imageContainer = $scope.find('.bw-il-image-container');
        const $image = $scope.find('.bw-il-hover-image');
        const $wrapper = $scope.find('.bw-il-hover-wrapper');
        const $menuItems = $scope.find('.bw-il-menu li');

        if (!$wrapper.length || !$image.length || !$menuItems.length || !$imageContainer.length) {
            return;
        }

        // Escape widget overflow / stacking contexts so follow stays clean.
        const $homeParent = $imageContainer.parent();
        $imageContainer.appendTo(document.body);

        const ns = NS + '-' + Date.now() + '-' + Math.floor(Math.random() * 10000);
        const brightnessNormal = gsapSettings.brightnessNormal || 1;
        const fadeIn = gsapSettings.fadeIn || 0.35;
        const fadeOut = gsapSettings.fadeOut || 0.25;
        const duration = gsapSettings.duration || 0.28;
        const ease = gsapSettings.ease || 'power2.out';

        // Preload item images so swaps don't hitch.
        $menuItems.each(function () {
            const src = $(this).data('image');
            if (src && typeof src === 'string') {
                const img = new Image();
                img.src = src;
            }
        });

        // Touch / coarse pointer: keep static reveal near the list (no cursor follow).
        if (prefersReducedMotion() || isCoarsePointer()) {
            const api = {
                ns: ns,
                rafId: 0,
                running: false,
                active: false,
                $wrapper: $wrapper,
                $image: $image,
                $menuItems: $menuItems,
                $imageContainer: $imageContainer,
                $homeParent: $homeParent,
            };

            // Put layer back for reduced / touch - position relative to widget.
            $imageContainer.appendTo($homeParent);
            $imageContainer.css({
                position: 'absolute',
                inset: 'auto 24px 24px auto',
                width: 'auto',
                height: 'auto',
                zIndex: 5,
            });
            gsap.set($wrapper, {
                autoAlpha: 0,
                clearProps: 'x,y,rotationX,rotationY,rotationZ',
            });
            gsap.set($image, { opacity: 0, filter: 'brightness(' + brightnessNormal + ')' });

            $menuItems.on('mouseenter.' + ns + ' focus.' + ns, function () {
                const imgSrc = $(this).data('image');
                if (imgSrc) {
                    $image.attr('src', imgSrc);
                }
                gsap.to($wrapper, { autoAlpha: 1, duration: duration, ease: ease });
                gsap.to($image, { opacity: 1, duration: fadeIn, ease: ease });
            });

            $menuItems.on('mouseleave.' + ns + ' blur.' + ns, function () {
                gsap.to($wrapper, { autoAlpha: 0, duration: duration, ease: ease });
                gsap.to($image, { opacity: 0, duration: fadeOut, ease: ease });
            });

            $scope.data(NS, api);
            $scope.attr('data-bw-il-ready', '1');
            return;
        }

        const mouse = { x: window.innerWidth / 2, y: window.innerHeight / 2 };
        const pos = { x: mouse.x, y: mouse.y };
        const rot = { x: 0, y: 0, z: 0 };
        // Tracks the cursor's own last position (not the lagging follow
        // position) so the Z tilt reflects real, instantaneous mouse
        // velocity and settles back to 0 as soon as the cursor stops.
        let lastMouseX = mouse.x;

        // Follow / tilt - tuned for a ~280px card (not a full-viewport sheet).
        const followSpeed = typeof gsapSettings.followSpeed === 'number' ? gsapSettings.followSpeed : 0.22;
        const rotSpeed = typeof gsapSettings.rotSpeed === 'number' ? gsapSettings.rotSpeed : 0.14;
        const maxRotX = gsapSettings.maxRotX != null ? gsapSettings.maxRotX : 8;
        const maxRotY = gsapSettings.maxRotY != null ? gsapSettings.maxRotY : 8;
        const maxTransX = gsapSettings.maxTransX != null ? gsapSettings.maxTransX : 14;
        const maxTransY = gsapSettings.maxTransY != null ? gsapSettings.maxTransY : 14;
        const brightnessStrength = gsapSettings.brightnessStrength || 1.12;
        const rotZStrength = gsapSettings.rotZStrength != null ? gsapSettings.rotZStrength : 0.12;

        gsap.set($image, { filter: 'brightness(' + brightnessNormal + ')' });
        gsap.set($wrapper, {
            autoAlpha: 0,
            x: 0,
            y: 0,
            rotationX: 0,
            rotationY: 0,
            rotationZ: 0,
            force3D: true,
        });

        const api = {
            ns: ns,
            rafId: 0,
            running: true,
            active: false,
            $wrapper: $wrapper,
            $image: $image,
            $menuItems: $menuItems,
            $imageContainer: $imageContainer,
            $homeParent: $homeParent,
        };

        function wrapperSize() {
            const el = $wrapper[0];
            return {
                w: el ? el.offsetWidth || 280 : 280,
                h: el ? el.offsetHeight || 350 : 350,
            };
        }

        function stopRaf() {
            if (api.rafId) {
                cancelAnimationFrame(api.rafId);
                api.rafId = 0;
            }
        }

        function render() {
            if (!api.running || !api.active) {
                api.rafId = 0;
                return;
            }

            pos.x += (mouse.x - pos.x) * followSpeed;
            pos.y += (mouse.y - pos.y) * followSpeed;

            // Settle tiny residual so motion doesn't crawl forever.
            if (Math.abs(mouse.x - pos.x) < 0.15) {
                pos.x = mouse.x;
            }
            if (Math.abs(mouse.y - pos.y) < 0.15) {
                pos.y = mouse.y;
            }

            // Use the raw cursor delta (not the lagging follow position) for the
            // Z tilt, so the image sits flat by default and only shakes while the
            // mouse is actually moving, easing back to 0 the instant it stops.
            const velocityX = mouse.x - lastMouseX;
            lastMouseX = mouse.x;
            const relX = (mouse.x / window.innerWidth - 0.5) * 2;
            const relY = (mouse.y / window.innerHeight - 0.5) * 2;

            const targetRotX = gsap.utils.clamp(-maxRotX, maxRotX, -relY * maxRotX);
            const targetRotY = gsap.utils.clamp(-maxRotY, maxRotY, relX * maxRotY);
            const targetRotZ = gsap.utils.clamp(-6, 6, velocityX * rotZStrength);
            const offsetX = gsap.utils.clamp(-maxTransX, maxTransX, relX * maxTransX);
            const offsetY = gsap.utils.clamp(-maxTransY, maxTransY, relY * maxTransY);

            rot.x += (targetRotX - rot.x) * rotSpeed;
            rot.y += (targetRotY - rot.y) * rotSpeed;
            rot.z += (targetRotZ - rot.z) * rotSpeed;

            const size = wrapperSize();
            // Anchor near cursor: slight right + above, scaled to card size.
            const anchorX = size.w * 0.18;
            const anchorY = size.h * 0.28;

            gsap.set($wrapper, {
                x: pos.x + anchorX + offsetX,
                y: pos.y - anchorY + offsetY,
                rotationX: rot.x,
                rotationY: rot.y,
                rotationZ: rot.z,
                transformPerspective: 900,
                transformOrigin: 'center center',
                force3D: true,
            });

            api.rafId = requestAnimationFrame(render);
        }

        function startRaf() {
            if (!api.running || api.rafId) {
                return;
            }
            api.rafId = requestAnimationFrame(render);
        }

        function snapToCursor() {
            pos.x = mouse.x;
            pos.y = mouse.y;
            rot.x = 0;
            rot.y = 0;
            rot.z = 0;
            lastMouseX = mouse.x;

            const size = wrapperSize();
            gsap.set($wrapper, {
                x: pos.x + size.w * 0.18,
                y: pos.y - size.h * 0.28,
                rotationX: 0,
                rotationY: 0,
                rotationZ: 0,
            });
        }

        $scope.data(NS, api);
        $scope.attr('data-bw-il-ready', '1');

        $menuItems.on('mouseenter.' + ns, function () {
            api.active = true;
            snapToCursor();
            startRaf();

            gsap.to($wrapper, {
                autoAlpha: 1,
                duration: duration,
                ease: ease,
                overwrite: 'auto',
            });

            const imgSrc = $(this).data('image');
            if (imgSrc) {
                if ($image.attr('src') !== imgSrc) {
                    $image.attr('src', imgSrc);
                }
                gsap.to($image, {
                    opacity: 1,
                    filter: 'brightness(' + brightnessStrength + ')',
                    duration: fadeIn,
                    ease: ease,
                    overwrite: 'auto',
                });
            }
        });

        $menuItems.on('mouseleave.' + ns, function () {
            api.active = false;
            stopRaf();

            gsap.to($wrapper, {
                autoAlpha: 0,
                duration: duration,
                ease: ease,
                overwrite: 'auto',
            });
            gsap.to($image, {
                opacity: 0,
                filter: 'brightness(' + brightnessNormal + ')',
                duration: fadeOut,
                ease: ease,
                overwrite: 'auto',
            });
        });

        $(window).on('mousemove.' + ns, function (e) {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/b_gsap_interactive_links.default',
            function ($scope) {
                initBWInteractiveWidget($scope);
            }
        );
    });
})(jQuery);
