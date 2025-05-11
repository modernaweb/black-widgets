jQuery(document).ready(function() {
    const $targetElements = jQuery('.bw-text-animate');

    $targetElements.each(function() {
        const split = jQuery(this).data('split') || 'none';
        const text = jQuery(this).text();

        let html;
        if (split === 'none') {
            html = '<span class="bw-text-animate-content">' + text + '</span>';
        } else if (split === 'letter') {
            html = text.replace(/\S/g, '<span class="bw-text-animate-content">$&</span>');
        } else {
            html = text.split(/\s+/).map(function(word) {
                return '<span class="bw-text-animate-content">' + word + '</span>';
            }).join(' ');
        }
        jQuery(this).html(html);
    });

    const handleVisibilityChange = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const animation = jQuery(entry.target).data('animation') || 'ftop';
                const delay = parseInt(jQuery(entry.target).data('delay')) || 500;

                const timeline = anime.timeline({easing: 'easeOutExpo', duration: delay});
                jQuery(entry.target).find('.bw-text-animate-content').each(function() {
                    if (animation === 'ftop') {
                        timeline.add({
                            targets: this,
                            translateY: ['-100%', 0],
                            opacity: [0 ,1],
                        });
                    } else if (animation === 'fbottom') {
                        timeline.add({
                            targets: this,
                            translateY: ['100%', 0],
                            opacity: [0 ,1],
                        });
                    } else if (animation === 'fleft') {
                        timeline.add({
                            targets: this,
                            translateX: [-100, 0],
                            opacity: [0 ,1],
                        });
                    } else if (animation === 'fright') {
                        timeline.add({
                            targets: this,
                            translateX: [100, 0],
                            opacity: [0 ,1],
                        });
                    } else {
                        timeline.add({
                            targets: this,
                            opacity: [0 ,1],
                            duration: delay,
                        });
                    }
                });

                observer.unobserve(entry.target);
            }
        });
    };

    const observer = new IntersectionObserver(handleVisibilityChange, {
        threshold: 0.4
    });

    $targetElements.each(function() {
        observer.observe(this);
    });
});
