jQuery(document).ready(function() {
    'use strict';

    let setupScrollTrigger;
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        setupScrollTrigger = function() {
            const sections = document.querySelectorAll('.bw-section');

            sections.forEach(section => {
                if (section.scrollTriggerSet) return;

                const elements = gsap.utils.toArray('.elementor', section);

                if (elements.length > 0) {
                    gsap.to(elements, {
                        xPercent: -100 * (elements.length - 1),
                        ease: "none",
                        scrollTrigger: {
                            trigger: section,
                            pin: true,
                            scrub: 1,
                            snap: directionalSnap(1 / (elements.length - 1)),
                            // Set the end based on the number of elements
                            end: "+=" + (section.offsetWidth * elements.length),
                        }
                    });

                    // Mark this section as having the ScrollTrigger set, to avoid reapplying
                    section.scrollTriggerSet = true;
                }
            });
        }

        // Helper function for directional snap
        function directionalSnap(increment) {
            const snapFunc = gsap.utils.snap(increment);
            return (raw, self) => {
                const n = snapFunc(raw);
                return Math.abs(n - raw) < 1e-4 || (n < raw) === self.direction < 0
                    ? n
                    : self.direction < 0
                    ? n - increment
                    : n + increment;
            };
        }
    }

    function applyTiltEffect($elements) {
        $elements.tilt({
            scale: 1.1,
            speed: 1000
        });
    }


    function iconBoxAnimate() {
        const objectSelect = jQuery('.bw-iconbox-svg-animate');
        if (!objectSelect.length) {
            return;
        }
        const scroll = jQuery(window).scrollTop();
        const bottom = jQuery(window).height();
        const objectPosition = objectSelect.offset().top - bottom;
        if (scroll > objectPosition) {
            objectSelect.addClass('run');
        } else {
            objectSelect.removeClass('run');
        }
    }

    function imageParallax() {
        if (typeof window.bwInitImageParallax === 'function') {
            window.bwInitImageParallax(document);
            return;
        }
        if (typeof simpleParallax === 'undefined') {
            return;
        }
        var nodes = document.querySelectorAll('img.bw-parallax');
        for (var i = 0; i < nodes.length; i++) {
            if (nodes[i] && !nodes[i].closest('.simpleParallax')) {
                new simpleParallax(nodes[i]);
            }
        }
    }

    function titleAnimatePreview(scope) {
        if (typeof window.bwInitTitleAnimate !== 'function') {
            return;
        }
        const root = scope && scope.nodeType === 1 ? scope : document;
        const widgets = root.matches && root.matches('.elementor-widget-b_TitleAnimate')
            ? [root]
            : Array.prototype.slice.call(root.querySelectorAll('.elementor-widget-b_TitleAnimate'));
        // Also cover bare title-anime roots when the Elementor wrapper is not present yet.
        if (!widgets.length) {
            const bare = root.querySelectorAll
                ? root.querySelectorAll('.bw-title-anime.bw-rotator, .bw-title-anime.bw-svg, .bw-title-anime.bw-classic, .bw-title-anime.bw-liner, .bw-title-anime.bw-typing, .bw-title-anime.bw-simple-wrap, .bw-title-anime.bw-effective')
                : [];
            Array.prototype.forEach.call(bare, function (el) {
                const wrap = el.closest('.elementor-widget-b_TitleAnimate') || el;
                window.bwInitTitleAnimate(jQuery(wrap));
            });
            return;
        }
        widgets.forEach(function (widget) {
            window.bwInitTitleAnimate(jQuery(widget));
        });
    }

    function typographyAnimate(scope) {
        if (typeof window.bwInitTypographyAnimate === 'function') {
            window.bwInitTypographyAnimate(scope || document);
        }
    }

    function imageCarousel(scope) {
        if (typeof window.bwInitImageCarousel === 'function') {
            window.bwInitImageCarousel(scope || document);
        }
    }

    // Match the node itself OR descendants — Elementor often injects the widget
    // root (or the animate element) as the added node; .find() alone misses that.
    function matchesIn(node, selector) {
        var $node = jQuery(node);
        return $node.filter(selector).add($node.find(selector));
    }

    var typographyBootTimer = null;
    function scheduleTypographyBoot(scope) {
        clearTimeout(typographyBootTimer);
        typographyBootTimer = setTimeout(function () {
            typographyAnimate(scope || document);
        }, 40);
    }

    const observer = new MutationObserver(function(mutationsList) {
        mutationsList.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Ensure it's an element node
                    let $newElements = matchesIn(node, '.bw-button-box.modern.m-4 .btn-wrapper');
                    if ($newElements.length && typeof jQuery.fn.tilt === 'function') {
                        applyTiltEffect($newElements); // Apply tilt effect to new elements
                    }

                    if (typeof setupScrollTrigger === 'function' ) {
                        $newElements = matchesIn(node, '.bw-section');
                        if ($newElements.length) {
                            setupScrollTrigger();
                        }
                    }

                    $newElements = matchesIn(node, '.bw-iconbox-svg-animate');
                    if ($newElements.length) {
                        iconBoxAnimate();
                    }

                    $newElements = matchesIn(node, '.bw-parallax');
                    if ($newElements.length) {
                        imageParallax();
                    }

                    $newElements = matchesIn(node, '.bw-title-anime, .elementor-widget-b_TitleAnimate');
                    if ($newElements.length) {
                        titleAnimatePreview(node);
                    }

                    $newElements = matchesIn(node, '.bw-typograpgy-animate, .bw-typograpgy-repetitive-wrap[data-bw-repetitive-anim], .bw-scroll-text, .elementor-widget-b_scroll_text, .elementor-widget-b_typography');
                    if ($newElements.length) {
                        scheduleTypographyBoot(node);
                    }

                    $newElements = matchesIn(node, '.bw-swiper, .elementor-widget-b_image_carousel');
                    if ($newElements.length) {
                        imageCarousel(node);
                    }
                }
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });

    // Boot typography / scroll-text FX for widgets already on the canvas.
    // element_ready can race before typography.js / SplitText is parsed.
    function bootTypographyAndCarousel() {
        typographyAnimate(document);
        imageCarousel(document);
    }
    bootTypographyAndCarousel();
    setTimeout(bootTypographyAndCarousel, 200);
    setTimeout(bootTypographyAndCarousel, 600);
    setTimeout(bootTypographyAndCarousel, 1500);
    setTimeout(bootTypographyAndCarousel, 3000);
});
