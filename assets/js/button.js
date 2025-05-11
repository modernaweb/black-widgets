jQuery(document).ready(function() {
    'use strict';

    function applyTiltEffect($elements) {
        $elements.tilt({ 
            scale: 1.1, 
            speed: 1000 
        });
    }

    applyTiltEffect(jQuery('.bw-button-box.modern.m-4 .btn-wrapper'));

    // click event
    jQuery(document).on('click', '.bw-button-box.modern.m-4 .btn-wrapper', function(e) {
        explode(e.pageX, e.pageY);
    });

    jQuery(document).on('click', '.bw-button-box.modern.m-4 .bw-btn-m-4', function(e) {
        e.preventDefault();
        setTimeout(function(url) { window.location = url }, 360, this.href);
    });

    document.addEventListener("touchstart", function() {}, true);

    // symbols
    function explode(x, y) {

        var symbolArray = [
            '#donut',
            '#circle',
            '#tri_hollow',
            '#triangle',
            '#square',
            '#squ_hollow'
        ];

        let particles = 10,
            explosion = jQuery('.bw-button-box.modern.m-4 .btn-wrapper');

        for (let i = 0; i < particles; i++) {

            let randomSymbol = Math.floor(Math.random() * symbolArray.length);
            // positioning x,y of the particles
            let x = (explosion.width() / 2) + rand(80, 150) * Math.cos(2 * Math.PI * i / rand(particles - 10, particles + 10)),
                y = (explosion.height() / 2) + rand(80, 150) * Math.sin(2 * Math.PI * i / rand(particles - 10, particles + 10)),
                deg = rand(0, 360) + 'deg',
                scale = rand(0.5, 1.1),
                // particle element creation
                elm = jQuery(
                    '<svg class="shape" style="top:' + y + 'px; left:' + x + 'px; transform: scale(' + scale + ') rotate(' + deg + ');">' +
                    '<use xlink:href="' + symbolArray[randomSymbol] + '" />' +
                    '</svg>'
                );
            if (i == 0) { // only need to target one of the symbols.
                // css3 animation end detection
                elm.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
                    elm.siblings('svg').remove().end().remove(); // remove particles when animation is over.
                });
            }
            explosion.prepend(elm);
        }
    }

    function rand(min, max) {
        return Math.floor(Math.random() * (max + 1)) + min;
    }

    const FPS = 7;
    const DURATION = 300;
    const CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const DELAY = ~~(300 / FPS);
    const FRAME_COUNT = ~~(DURATION / 200) * FPS;

    jQuery(document).on('mouseenter', '.bw-button-box.abstract.a-2 .btx-a1', function () {
        const $Element = jQuery(this);
        const TEXT = $Element.text();
        let frameIndex = 0;
        let timeoutId = undefined;

        function resetText() {
            if (timeoutId !== undefined) clearTimeout(timeoutId);
            frameIndex = 0;
            $Element.text(TEXT);
        }

        function setRandomText() {
            const text = Array.from({ length: TEXT.length }).map(() => CHARACTERS[~~(Math.random() * CHARACTERS.length)]);
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

    jQuery(document).on('mouseout', '.bw-button-box.abstract.a-2 .btx-a1', function () {
        const TEXT = jQuery(this).data('text');
        const timeoutId = jQuery(this).data('timeoutId');
        if (timeoutId !== undefined) {
            clearTimeout(timeoutId);
        }
        jQuery(this).text(TEXT);
    });

});
