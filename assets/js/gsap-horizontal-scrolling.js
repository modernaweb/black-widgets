jQuery(window).ready(function($) {
    gsap.registerPlugin(ScrollTrigger);

    function setupScrollTrigger() {
        const sections = document.querySelectorAll('.bw-section');

        sections.forEach(section => {
            if (section.scrollTriggerSet) return;

            const elements = gsap.utils.toArray('.elementor', section);

            if (elements.length > 0) {
                gsap.to(elements, {
                    xPercent: -100 * (elements.length - 1),
                    ease: "none",
                    scrollTrigger: {
                        trigger: section,
                        pin: true,
                        scrub: 1,
                        snap: directionalSnap(1 / (elements.length - 1)),
                        // Set the end based on the number of elements
                        end: "+=" + (section.offsetWidth * elements.length),
                    }
                });

                // Mark this section as having the ScrollTrigger set, to avoid reapplying
                section.scrollTriggerSet = true;
            }
        });
    }

    // Helper function for directional snap
    function directionalSnap(increment) {
        const snapFunc = gsap.utils.snap(increment);
        return (raw, self) => {
            const n = snapFunc(raw);
            return Math.abs(n - raw) < 1e-4 || (n < raw) === self.direction < 0
                ? n
                : self.direction < 0
                ? n - increment
                : n + increment;
        };
    }

    setupScrollTrigger();
});
