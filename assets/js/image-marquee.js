jQuery(document).ready(function() {
    jQuery('.bw-image-marquee:not(.bw-mos)').each(function() {
        const duration =  jQuery(this).data('duration');
        const $content = jQuery(this).find('.bw-image-marquee-content');

        $content.css('animation-duration', duration + 's');
    });

    if (typeof gsap !== 'undefined') {
        jQuery('.bw-image-marquee.bw-mos').each(function() {
            const $section = jQuery(this);
            const start = $section.data('start');
            const end = $section.data('end');

            const $w = $section.find('.bw-image-marquee-content');
            const movement = $w.data('movement');
            const direction = $section.data('direction');

            if ( movement === 'hortizontal' ) {
                const width = $w.outerWidth();
                const [x, xEnd] = direction == 'ltr' ? [-1 * width, '100%'] : ['100%', -1 * width];

                gsap.fromTo($w[0], { x }, {
                    x: xEnd,
                    scrollTrigger: { 
                        start: start,
                        end: end,
                        trigger: $section[0], 
                        scrub: true
                    }
                });
            } else {
                const height = $w.outerHeight();
                const [y, yEnd] = direction === 'ltr' ? [-1 * height, '100%'] : ['100%', -1 * height];

                gsap.fromTo($w[0], { y }, {
                    y: yEnd,
                    scrollTrigger: { 
                        start: start,
                        end: end,
                        trigger: $section[0], 
                        scrub: true
                    }
                });
            }
        });
    }
});
