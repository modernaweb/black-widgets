(function ($) {
    'use strict';

    /**
     * Init simpleParallax on .bw-parallax images that are not already wrapped.
     * simpleParallax 5.2 accepts HTMLElement | NodeList | HTMLCollection - not plain Arrays.
     *
     * @param {JQuery|HTMLElement|Document|null} scope
     */
    function initBwParallax(scope) {
        if (typeof simpleParallax === 'undefined') {
            return;
        }

        var root = scope && scope.jquery ? scope.get(0) : scope;
        var nodes;

        if (root && root.querySelectorAll) {
            nodes = root.querySelectorAll('img.bw-parallax');
        } else {
            nodes = document.querySelectorAll('img.bw-parallax');
        }

        for (var i = 0; i < nodes.length; i++) {
            var el = nodes[i];
            if (!el || el.closest('.simpleParallax')) {
                continue;
            }
            // Pass a single IMG node - plain arrays are treated as one invalid "element".
            new simpleParallax(el);
        }
    }

    // Expose for optional inline/BC callers.
    window.bwInitImageParallax = initBwParallax;

    $(function () {
        initBwParallax(document);
    });

    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
            return;
        }
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/b_image.default',
            function ($scope) {
                initBwParallax($scope);
            }
        );
    });
})(jQuery);
