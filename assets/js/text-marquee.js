function marquee() {
    // Loop through all marquee wrappers on the page
    document.querySelectorAll('.bw-text-marquee-wrapper').forEach(function (wrapper) {
        if (!wrapper) return;

        // Find the main inner element: multi-item or single-item marquee
        const inner = wrapper.querySelector('.bw-marquee-inner') || wrapper.querySelector('.bw-marquee-inner-single');
        if (!inner) return;

        // Retrieve settings from data attributes
        const settings = wrapper.dataset;
        const direction = settings.direction || 'left';
        const speed = parseFloat(settings.speed) || 50;
        const scrollControlled = settings.scrollControlled === "yes";
        const startCondition = settings.gsapStart || "top bottom";
        const endCondition = settings.gsapEnd || "bottom top";
        const gap = parseFloat(settings.gap) || 8;
        const speedScroll = parseFloat(settings.speedScroll) || 2;

        // Template element for cloning (only exists for multi-item marquee)
        const template = wrapper.querySelector('.bw-marquee-template');

        if (inner.classList.contains('bw-marquee-inner')) {
            // Multi-item marquee mode (can be scroll controlled or continuous CSS animation)

            if (!template) return;

            if (scrollControlled) {
                // Disable CSS animation when scroll-controlled
                inner.style.animation = 'none';

                // Ensure GSAP and ScrollTrigger are loaded before initializing animation
                if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                    gsap.registerPlugin(ScrollTrigger);

                    // Calculate how many clones are needed to fill twice the viewport width

                    const containerHeight = wrapper.offsetHeight;
                    const scrollDistance = window.innerHeight;

                    const singleWidth = template.offsetWidth;
                    const minWidth = window.innerWidth * 2;
                    const repeatCount = Math.ceil(minWidth / singleWidth);

                    for (let i = 1; i < repeatCount; i++) {
                        const clone = template.cloneNode(true);
                        clone.style.marginLeft = `${gap || 8}px`;
                        inner.appendChild(clone);
                    }

                    // Clone the template element to fill the marquee
                    for (let i = 1; i < repeatCount; i++) {
                        const clone = template.cloneNode(true);
                        clone.style.marginLeft = `${gap || 8}px`;
                        inner.appendChild(clone);
                    }

                    // const totalWidth = inner.offsetWidth;

                    // Determine start and end positions based on direction
                    // const startOffset = direction === 'right' ? 0 : -totalWidth;
                    // const endOffset = direction === 'right' ? -totalWidth : 0;

                    const movementDistance = window.innerWidth / speedScroll ;

                    gsap.fromTo(inner,
                        { x: direction === 'right' ? 0 : -movementDistance },
                        {
                            x: direction === 'right' ? -movementDistance : 0,
                            ease: "none",
                            scrollTrigger: {
                                trigger: wrapper,
                                start: startCondition,
                                end: endCondition,
                                scrub: 1.5,
                                invalidateOnRefresh: true
                            }
                        }
                    );

                    // Refresh ScrollTrigger to recalculate all trigger positions
                    ScrollTrigger.refresh();
                }
                return;
            }

            // Continuous CSS animation mode (not scroll controlled)

            // Create a temporary clone to measure width of a single template item
            const tempClone = template.cloneNode(true);
            Object.assign(tempClone.style, {
                visibility: 'hidden',
                position: 'absolute',
                whiteSpace: 'nowrap',
            });
            document.body.appendChild(tempClone);
            const singleWidth = tempClone.offsetWidth;
            document.body.removeChild(tempClone);

            // Calculate how many clones are needed to fill twice the viewport width
            const repeatCount = Math.ceil(window.innerWidth * 3 / singleWidth);


            for (let i = 1; i < repeatCount; i++) {
                const clone = template.cloneNode(true);
                clone.style.marginLeft = `${gap || 8}px`;
                inner.appendChild(clone);
            }

            // Clone the template element to fill the marquee container
            for (let i = 1; i < repeatCount; i++) {
                const clone = template.cloneNode(true);
                clone.style.marginLeft = `${gap || 8}px`;
                inner.appendChild(clone);
            }

            const totalWidth = inner.offsetWidth;
            const duration = totalWidth / speed;
            const animationName = direction === 'right' ? 'bw-marquee-left' : 'bw-marquee-right' ;

            // Apply CSS animation for continuous marquee scrolling
            inner.style.animation = `${animationName} ${duration}s linear infinite`;
        }
        else if (inner.classList.contains('bw-marquee-inner-single')) {
            // Single-item marquee mode with dynamic CSS keyframe animation

            const wrapperWidth = wrapper.offsetWidth;

            // Clone the inner element temporarily to measure text width
            const temp = inner.cloneNode(true);
            Object.assign(temp.style, {
                position: "absolute",
                visibility: "hidden",
                whiteSpace: "nowrap"
            });
            document.body.appendChild(temp);
            const textWidth = temp.offsetWidth;
            document.body.removeChild(temp);

            // Calculate start and end positions based on direction
            const start = direction === "right" ? wrapperWidth : -textWidth;
            const end = direction === "right" ? -textWidth : wrapperWidth;
            const distance = Math.abs(end - start);
            const duration = distance / speed;

            // Generate a unique animation name to avoid conflicts
            const keyframesName = `bw-marquee-${direction}-${Date.now()}`;
            const style = document.createElement("style");
            style.innerHTML = `
                @keyframes ${keyframesName} {
                    0% {
                        transform: translateX(${start}px);
                    }
                    100% {
                        transform: translateX(${end}px);
                    }
                }
            `;
            document.head.appendChild(style);

            // Apply the dynamically created CSS animation to the inner element
            inner.style.animation = `${keyframesName} ${duration}s linear infinite`;
        }
    });
}

// Run the marquee function immediately on page load
(function () {
    marquee();
})();

// Re-run the marquee initialization when Elementor frontend is ready for the specific widget
jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/b_text_marquee.default', function () {
        marquee();
    });
});
