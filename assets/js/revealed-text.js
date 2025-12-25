(function($){

    function initBwRevealedText($containers) {
        $containers.each(function () {
            const $container = $(this);
            const mode = ($container.data('mode') || 'word').toLowerCase();
            const effect = ($container.data('effect') || 'fade').toLowerCase();

            const $text = $container.find('p');
            let textContent = $text.text();
            $text.html('');

            const $spans = [];

            if (mode === 'letter') {
                for (let char of textContent) {
                    if (char === ' ') {
                        $text.append(' ');
                    } else {
                        const $span = $('<span>').text(char).addClass('bw-effect').css({opacity: 0.2});
                        $text.append($span);
                        $spans.push($span);
                    }
                }
            } else {
                const wordsAndSpaces = textContent.split(/(\s+)/);
                wordsAndSpaces.forEach((item) => {
                    if (item.trim() !== '') {
                        const $span = $('<span>').text(item).addClass('bw-effect').css({opacity: 0.2});
                        $text.append($span);
                        $spans.push($span);
                    } else {
                        $text.append(' ');
                    }
                });
            }

            function applyEffect($el, progress) {
                switch (effect) {
                    case 'zoom':
                        const scale = 1.2 - progress * 0.2;
                        $el.css({
                            'transform': progress >= 1 ? 'scale(1)' : `scale(${scale})`,
                            'filter': ''
                        });
                        break;

                    case 'rotate':
                        const rotateDeg = (1 - progress) * 20;
                        $el.css({
                            'transform': progress >= 1 ? 'rotate(0deg)' : `rotate(${rotateDeg}deg)`,
                            'filter': ''
                        });
                        break;

                    case 'blur':
                        const blur = (1 - progress) * 5;
                        $el.css({
                            'transform': '',
                            'filter': progress >= 1 ? 'blur(0px)' : `blur(${blur}px)`
                        });
                        break;

                    case 'fade':
                    default:
                        $el.css({
                            'transform': '',
                            'filter': ''
                        });
                        break;
                }
            }

            function handleScroll() {
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();

                const containerTop = $container.offset().top;
                const containerHeight = $container.outerHeight();

                const animationStart = containerTop - windowHeight+100;
                const animationEnd = containerTop + containerHeight-250;

                const totalRange = animationEnd - animationStart;
                const currentScroll = scrollTop - animationStart;

                const progress = Math.min(Math.max(currentScroll / totalRange, 0), 1);

                const step = 1 / $spans.length;

                $spans.forEach(($span, index) => {
                    const localProgress = (progress - index * step) / step;

                    let opacity = 0.2 + localProgress * (1 - 0.2);
                    opacity = Math.min(Math.max(opacity, 0.2), 1);

                    $span.css('opacity', opacity);
                    applyEffect($span, Math.min(Math.max(localProgress, 0), 1));
                });
            }

            $(window).on('scroll', handleScroll);
            handleScroll();
        });
    }


    jQuery(document).ready(function($) {
        const $containers = $('.bw-revealed-text');
        if ($containers.length) {
            initBwRevealedText($containers);
        }
    });

    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/b_revealed_text.default', function ($scope) {
            const $containers = $scope.find('.bw-revealed-text');
            if ($containers.length) {
                initBwRevealedText($containers);
            }
        });
    });

})(jQuery);
