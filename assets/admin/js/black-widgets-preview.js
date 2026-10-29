jQuery(document).ready(function() {
    'use strict';

    var horizontalPreviewTimer = null;
    function horizontalPreview(scope) {
        window.clearTimeout(horizontalPreviewTimer);
        horizontalPreviewTimer = window.setTimeout(function () {
            if (typeof window.bwInitHorizontalScrolling === 'function') {
                window.bwInitHorizontalScrolling(scope || document);
            }
        }, 120);
    }

    function applyTiltEffect($elements) {
        $elements.tilt({
            scale: 1.1,
            speed: 1000
        });
    }


    function iconBoxAnimate(scope) {
        if (typeof window.bwInitIconBox === 'function') {
            window.bwInitIconBox(jQuery(scope || document));
            return;
        }
        const objectSelect = jQuery(scope || document).find('.bw-iconbox-svg-animate');
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

    function bootIconBox() {
        iconBoxAnimate(document);
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
                if (node.nodeType === 1) {
                    let $newElements = matchesIn(node, '.bw-button-box.modern.m-4 .btn-wrapper');
                    if ($newElements.length && typeof jQuery.fn.tilt === 'function') {
                        applyTiltEffect($newElements);
                    }

                    $newElements = matchesIn(node, '.bw-section, .bw-horizontal-section, .elementor-widget-b_gsap_horizontal_scrolling');
                    if ($newElements.length) {
                        horizontalPreview(node);
                    }

                    $newElements = matchesIn(node, '.bw-iconbox-svg-animate, .bw-iconbox[data-bw-anim], .elementor-widget-b_icon_box');
                    if ($newElements.length) {
                        iconBoxAnimate(node);
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

    // Boot typography / scroll-text FX for widgets already on the canvas - element_ready can race before typography.js / SplitText is parsed.
    function bootTypographyAndCarousel() {
        typographyAnimate(document);
        imageCarousel(document);
    }
    bootTypographyAndCarousel();
    bootIconBox();
    setTimeout(bootTypographyAndCarousel, 200);
    setTimeout(bootIconBox, 200);
    setTimeout(bootTypographyAndCarousel, 600);
    setTimeout(bootIconBox, 800);
    setTimeout(bootTypographyAndCarousel, 1500);
    setTimeout(bootTypographyAndCarousel, 3000);
    horizontalPreview(document);
    setTimeout(function () {
        horizontalPreview(document);
    }, 200);
});
