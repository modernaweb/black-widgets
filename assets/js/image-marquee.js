function imageMarquee() {
    const marquees = document.querySelectorAll('.bw-image-marquee-wrapper');

    marquees.forEach(wrapper => {
        const track = wrapper.querySelector('.bw-image-marquee-track');
        const items = Array.from(track.children);

        const speed = parseFloat(wrapper.dataset.speed) || 20;
        const rawDirection = (wrapper.dataset.direction || 'left').toLowerCase();
        const type = wrapper.dataset.type || 'horizontal';
        const useGsap = wrapper.dataset.gsapScroll === 'true';
        const pauseOnHover = wrapper.dataset.pauseHover === 'true';
        const startCondition = wrapper.dataset.gsapStart || "top bottom";
        const endCondition = wrapper.dataset.gsapEnd || "bottom top";
        const speedScroll = parseFloat(wrapper.dataset.speedScroll) || 1;

        const isVertical = type === 'vertical';

        // normalize direction: allow left/right/up/down, and treat "right" as "down" in vertical mode for backward-compat
        let direction;
        if (isVertical) {
            direction = (rawDirection === 'down' || rawDirection === 'right') ? 'down' : 'up';
        } else {
            direction = (rawDirection === 'right' || rawDirection === 'down') ? 'right' : 'left';
        }

        // ensure we have enough content (2x wrapper) for seamless scroll in both directions
        if (items.length > 0) {
            cloneUntilFill(wrapper, track, items, 2, isVertical);
        }

        // add helper classes (useful for CSS mode too)
        if (isVertical) {
            track.classList.add('vertical', direction === 'down' ? 'scroll-down' : 'scroll-up');
        } else {
            track.classList.add(direction === 'right' ? 'scroll-right' : 'scroll-left');
        }

        // GSAP Mode
        if (useGsap && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            const scrollProp = isVertical ? 'y' : 'x';

            // For negative direction (left/up): 0% -> -50%
            // For positive direction (right/down): -50% -> 0%
            const fromVal = (direction === 'right' || direction === 'down') ? '-50%' : '0%';
            const toVal   = (direction === 'right' || direction === 'down') ? '0%'   : '-50%';

            // set will-change for smoother perf
            track.style.willChange = 'transform';

            gsap.fromTo(track,
                { [scrollProp]: fromVal },
                {
                    [scrollProp]: toVal,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: wrapper,
                        start: startCondition,
                        end: endCondition,
                        scrub: speedScroll,
                        invalidateOnRefresh: true
                    }
                }
            );
        }
        // CSS Animation Mode
        else {
            track.style.animationDuration = `${speed}s`;

            if (pauseOnHover) {
                wrapper.addEventListener('mouseenter', () => {
                    track.style.animationPlayState = 'paused';
                });
                wrapper.addEventListener('mouseleave', () => {
                    track.style.animationPlayState = 'running';
                });
            }
        }
    });
}

function cloneUntilFill(wrapper, track, items, multiplier = 2, isVertical = false) {
    let attempts = 0;
    const maxAttempts = multiplier * 3;

    const getSize = () => isVertical ? track.scrollHeight : track.scrollWidth;
    const getWrapperSize = () => isVertical ? wrapper.offsetHeight : wrapper.offsetWidth;

    while (getSize() < getWrapperSize() * multiplier && attempts < maxAttempts) {
        items.forEach(item => track.appendChild(item.cloneNode(true)));
        attempts++;
    }
}

document.addEventListener('DOMContentLoaded', imageMarquee);

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/b_image_marquee.default', imageMarquee);
});
