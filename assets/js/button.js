jQuery(function ($) {
    'use strict';

    function applyTiltEffect($elements) {
        if (!$elements.length || typeof $.fn.tilt !== 'function') {
            return;
        }
        $elements.each(function () {
            var $el = $(this);
            if ($el.data('bw-tilt-ready')) {
                return;
            }
            $el.tilt({
                scale: 1.1,
                speed: 1000
            });
            $el.data('bw-tilt-ready', true);
        });
    }

    function initButtonWidget($scope) {
        var $root = $scope && $scope.length ? $scope : $(document);
        applyTiltEffect($root.find('.bw-button-box.modern.m-4 .btn-wrapper'));
    }

    function getSymbolIds($wrapper) {
        var $symbols = $wrapper.siblings('svg[data-bw-symbols]').find('symbol');
        if (!$symbols.length) {
            $symbols = $wrapper.closest('.bw-button-box').find('svg[data-bw-symbols] symbol');
        }
        var ids = [];
        $symbols.each(function () {
            if (this.id) {
                ids.push('#' + this.id);
            }
        });
        return ids.length ? ids : [
            '#donut',
            '#circle',
            '#tri_hollow',
            '#triangle',
            '#square',
            '#squ_hollow'
        ];
    }

    function explode(x, y, $explosion) {
        var symbolArray = getSymbolIds($explosion);
        var particles = 10;

        for (var i = 0; i < particles; i++) {
            var randomSymbol = Math.floor(Math.random() * symbolArray.length);
            var px = ($explosion.width() / 2) + rand(80, 150) * Math.cos(2 * Math.PI * i / rand(particles - 10, particles + 10));
            var py = ($explosion.height() / 2) + rand(80, 150) * Math.sin(2 * Math.PI * i / rand(particles - 10, particles + 10));
            var deg = rand(0, 360) + 'deg';
            var scale = rand(0.5, 1.1);
            var elm = $(
                '<svg class="shape" style="top:' + py + 'px; left:' + px + 'px; transform: scale(' + scale + ') rotate(' + deg + ');">' +
                '<use xlink:href="' + symbolArray[randomSymbol] + '" />' +
                '</svg>'
            );

            if (i === 0) {
                elm.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function () {
                    elm.siblings('svg.shape').remove().end().remove();
                });
            }
            $explosion.prepend(elm);
        }
    }

    function rand(min, max) {
        return Math.floor(Math.random() * (max + 1)) + min;
    }

    $(document).on('click', '.bw-button-box.modern.m-4 .btn-wrapper', function (e) {
        explode(e.pageX, e.pageY, $(this));
    });

    $(document).on('click', '.bw-button-box.modern.m-4 .bw-btn-m-4', function (e) {
        // Allow modified / middle / non-primary clicks to use native link behavior.
        if (e.which > 1 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
            return;
        }

        e.preventDefault();
        var url = this.href;
        setTimeout(function () {
            window.location = url;
        }, 360);
    });

    document.addEventListener('touchstart', function () {}, true);

    var FPS = 7;
    var DURATION = 300;
    var CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var DELAY = ~~(300 / FPS);
    var FRAME_COUNT = ~~(DURATION / 200) * FPS;

    $(document).on('mouseenter', '.bw-button-box.abstract.a-2 .btx-a1', function () {
        var $Element = $(this);
        var previousTimeout = $Element.data('timeoutId');
        if (previousTimeout !== undefined) {
            clearTimeout(previousTimeout);
        }

        var TEXT = $Element.data('text');
        if (typeof TEXT !== 'string') {
            TEXT = $Element.text();
            $Element.data('text', TEXT);
        }

        var frameIndex = 0;
        var timeoutId;

        function resetText() {
            if (timeoutId !== undefined) {
                clearTimeout(timeoutId);
            }
            frameIndex = 0;
            $Element.text(TEXT);
            $Element.removeData('timeoutId');
        }

        function setRandomText() {
            var text = Array.from({ length: TEXT.length }).map(function () {
                return CHARACTERS[~~(Math.random() * CHARACTERS.length)];
            });
            $Element.text(text.join(''));
        }

        function animate() {
            if (frameIndex >= FRAME_COUNT) {
                resetText();
            } else {
                frameIndex += 1;
                setRandomText();
                timeoutId = setTimeout(animate, DELAY);
                $Element.data('timeoutId', timeoutId);
            }
        }

        animate();
    });

    $(document).on('mouseleave', '.bw-button-box.abstract.a-2 .btx-a1', function () {
        var $Element = $(this);
        var TEXT = $Element.data('text');
        var timeoutId = $Element.data('timeoutId');
        if (timeoutId !== undefined) {
            clearTimeout(timeoutId);
            $Element.removeData('timeoutId');
        }
        if (typeof TEXT === 'string') {
            $Element.text(TEXT);
        }
    });

    // Frontend / editor init with Elementor hook when available.
    function bindElementorReady() {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
            return false;
        }
        elementorFrontend.hooks.addAction('frontend/element_ready/b_button.default', function ($scope) {
            initButtonWidget($scope);
        });
        return true;
    }

    if (!bindElementorReady()) {
        $(window).on('elementor/frontend/init', bindElementorReady);
        initButtonWidget($(document));
    }
});
