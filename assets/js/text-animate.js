function bwTextAnimateInit($scope) {
    const $targetElements = $scope.find('.bw-text-animate');

    const handleVisibilityChange = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const $el = jQuery(entry.target);
                const animation = $el.data('animation') || 'ftop';
                const delay = parseInt($el.data('delay')) || 500;

                const timeline = anime.timeline({ easing: 'easeOutExpo', duration: delay });
                $el.find('.bw-text-animate-content').each(function () {
                    timeline.add({
                        targets: this,
                        translateY: animation === 'ftop' ? ['-100%', 0] :
                            animation === 'fbottom' ? ['100%', 0] : undefined,
                        translateX: animation === 'fleft' ? [-100, 0] :
                            animation === 'fright' ? [100, 0] : undefined,
                        opacity: [0, 1],
                    });
                });

                observer.unobserve(entry.target);
            }
        });
    };

    const observer = new IntersectionObserver(handleVisibilityChange, {
        threshold: 0.4
    });

    $targetElements.each(function () {
        observer.observe(this);
    });
}

jQuery(document).ready(function () {
    bwTextAnimateInit(jQuery(document));
});

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/b_text_animate.default', function ($scope) {
        bwTextAnimateInit($scope);
    });
});

