jQuery(document).ready(function() {

    jQuery('.bw-text-marquee-content-type2').each(function() {
        const $marquee = jQuery(this);
        const marqueeWidth = $marquee.width();
        const $content = $marquee.find('.bw-text-marquee-text');

        const singleContentWidth = $content.outerWidth(true);

        let contentWidth = singleContentWidth;
        while (contentWidth < marqueeWidth) {
            contentWidth += singleContentWidth;
            if ( contentWidth > marqueeWidth ) {
                break
            }

            const tmp = $content.clone();
            $marquee.append(tmp);
        }
    });

    jQuery('.bw-text-marquee:not(.bw-mos)').each(function() {
        const duration =  jQuery(this).data('duration');
        const $content = jQuery(this).find('.bw-text-marquee-content');

        $content.css('animation-duration', duration + 's');
        const gap = jQuery(this).data('gap');

        // We use requestAnimationFrame to trigger a reflow
        requestAnimationFrame(() => {
            $content.get(0).style.setProperty("--gap", gap + '%');
            $content.get(1).style.setProperty("--gap", gap + '%');
        });
    });

    if (typeof gsap !== 'undefined') {
        function getTextWidth(pElement) {
            const $tempSpan = jQuery('<span></span>').css({
                'font': jQuery(pElement).css('font'),
                'white-space': 'nowrap',
                'position': 'absolute',
                'left': '-9999px'
            }).text(jQuery(pElement).text()).appendTo('body');

            const textWidth = $tempSpan.outerWidth();
            $tempSpan.remove();
            return textWidth;
        }

        jQuery('.bw-text-marquee.bw-mos').each(function() {
            const $section = jQuery(this);
            const start = $section.data('start');
            const end = $section.data('end');

            const $w = $section.find('.bw-text-marquee-content');
            const direction = $section.data('direction');
            const width = getTextWidth($w[0]);
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
        });
    }
});
