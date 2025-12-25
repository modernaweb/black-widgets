jQuery(document).ready(function() {
    'use strict';

    let setupScrollTrigger;
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        setupScrollTrigger = function() {
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
    }

    function applyTiltEffect($elements) {
        $elements.tilt({
            scale: 1.1,
            speed: 1000
        });
    }


    function typographyAnimate() {

        const elements = document.querySelectorAll('.bw-typograpgy-animate .word');

        elements.forEach((element) => {
            const text = element.textContent;
            const chars = text.split('');
            const html = chars.map((char) => `<span class="char" data-char="${char}">${char}</span>`).join('');
            element.innerHTML = html;
        });

        const wrapElements = (elems, wrapType, wrapClass) => {
            elems.forEach(char => {
                const wrapEl = document.createElement(wrapType);
                wrapEl.classList = wrapClass;
                char.parentNode.appendChild(wrapEl);
                wrapEl.appendChild(char);
            });
        }

        gsap.registerPlugin(ScrollTrigger);

        const typographyFX_1 = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][bw-data-bw-scroll-e-1]')];
        const typographyFX_2 = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][bw-data-bw-scroll-e-2]')];
        const typographyFX_3 = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][bw-data-bw-scroll-e-3]')];
        const typographyFX_4 = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][bw-data-bw-scroll-e-4]')];
        const typographyFX_5 = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][bw-data-bw-scroll-e-5]')];
        const typographyFX_6 = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][bw-data-bw-scroll-e-6]')];
        const typographyFX_7 = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][bw-data-bw-scroll-e-7]')];
        const typographyFX_8 = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][bw-data-bw-scroll-e-8]')];

        const typographyFX_1S = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][scrub_mode][bw-data-bw-scroll-e-1]')];
        const typographyFX_2S = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][scrub_mode][bw-data-bw-scroll-e-2]')];
        const typographyFX_3S = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][scrub_mode][bw-data-bw-scroll-e-3]')];
        const typographyFX_4S = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][scrub_mode][bw-data-bw-scroll-e-4]')];
        const typographyFX_5S = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][scrub_mode][bw-data-bw-scroll-e-5]')];
        const typographyFX_6S = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][scrub_mode][bw-data-bw-scroll-e-6]')];
        const typographyFX_7S = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][scrub_mode][bw-data-bw-scroll-e-7]')];
        const typographyFX_8S = [...document.querySelectorAll('.bw-typograpgy-animate[bw-data-splitting][scrub_mode][bw-data-bw-scroll-e-8]')];

        // GSAP Scroll Triggers
        typographyFX_1.forEach(title => {
            const chars = title.querySelectorAll('.char');
            gsap.fromTo(chars, {
                'will-change': 'opacity, transform',
                opacity: 0,
                scale: 0.4,
                rotation: 45,
                rotationZ: () => gsap.utils.random(-20,20),
                duration: 0.3
            },
                {
                    ease: 'power4',
                    opacity: 1,
                    scale: 1,
                    rotation: 0,
                    stagger: 0.06,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        // scrub: true
                    },
                });
        });

        typographyFX_1S.forEach(title => {
            const chars = title.querySelectorAll('.char');
            gsap.fromTo(chars, {
                'will-change': 'opacity, transform',
                opacity: 0,
                scale: 0.4,
                rotation: 45,
                rotationZ: () => gsap.utils.random(-20,20),
                duration: 0.3
            },
                {
                    ease: 'power4',
                    opacity: 1,
                    scale: 1,
                    rotation: 0,
                    stagger: 0.06,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        scrub: true
                    },
                });
        });

        typographyFX_2.forEach(title => {
            const chars = title.querySelectorAll('.char');
            gsap.fromTo(chars, {
                'will-change': 'opacity, transform',
                opacity: 0,
                yPercent: 120,
                scaleY: 2.3,
                scaleX: 0.3,
            },
                {
                    opacity: 1,
                    yPercent: 1,
                    scaleY: 1,
                    scaleX: 1,
                    ease: 'power4',
                    stagger: 0.06,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        // scrub: true
                    }
                });
        });

        typographyFX_2S.forEach(title => {
            const chars = title.querySelectorAll('.char');
            gsap.fromTo(chars, {
                'will-change': 'opacity, transform',
                opacity: 0,
                yPercent: 120,
                scaleY: 2.3,
                scaleX: 0.3,
            },
                {
                    opacity: 1,
                    yPercent: 1,
                    scaleY: 1,
                    scaleX: 1,
                    ease: 'power4',
                    stagger: 0.06,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        scrub: true
                    }
                });
        });

        typographyFX_3.forEach(title => {
            const words = title.querySelectorAll('.word');
            for (const word of words) {
                const chars = word.querySelectorAll('.char');
                chars.forEach(char => gsap.set(char.parentNode, { perspective: 2000 }));
                gsap.fromTo(chars, {
                    'will-change': 'opacity, transform',
                    opacity: 0,
                    rotationX: -90,
                    yPercent: 50
                },
                    {
                        ease: 'power1.inOut',
                        opacity: 1,
                        rotationX: 0,
                        yPercent: 0,
                        stagger: {
                            each: 0.03,
                            from: 0
                        },
                        scrollTrigger: {
                            trigger: word,
                            start: 'center+=20% bottom',
                            end: '+=50%',
                            // scrub: 0.9
                            // scrub: true
                        }
                    });
            }
        });

        typographyFX_3S.forEach(title => {
            const words = title.querySelectorAll('.word');
            for (const word of words) {
                const chars = word.querySelectorAll('.char');
                chars.forEach(char => gsap.set(char.parentNode, { perspective: 2000 }));
                gsap.fromTo(chars, {
                    'will-change': 'opacity, transform',
                    opacity: 0,
                    rotationX: -90,
                    yPercent: 50
                },
                    {
                        ease: 'power1.inOut',
                        opacity: 1,
                        rotationX: 0,
                        yPercent: 0,
                        stagger: {
                            each: 0.03,
                            from: 0
                        },
                        scrollTrigger: {
                            trigger: word,
                            start: 'center+=20% bottom',
                            end: '+=50%',
                            scrub: true
                        }
                    });
            }
        });

        typographyFX_4.forEach(title => {
            const chars = title.querySelectorAll('.char');
            wrapElements(chars, 'span', 'char-wrap');
            gsap.fromTo(chars, {
                'will-change': 'transform',
                transformOrigin: '0% 50%',
                xPercent: 105,
            },
                {
                    duration: 1,
                    ease: 'expo',
                    xPercent: 0,
                    stagger: 0.062,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        // toggleActions: "play resume resume reset",
                    }
                });
        });

        typographyFX_4S.forEach(title => {
            const chars = title.querySelectorAll('.char');
            wrapElements(chars, 'span', 'char-wrap');
            gsap.fromTo(chars, {
                'will-change': 'transform',
                transformOrigin: '0% 50%',
                xPercent: 105,
            },
                {
                    duration: 1,
                    ease: 'expo',
                    xPercent: 0,
                    stagger: 0.062,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        toggleActions: "play resume resume reset",
                    }
                });
        });

        typographyFX_5.forEach(title => {
            const chars = title.querySelectorAll('.char');
            wrapElements(chars, 'span', 'char-wrap');
            gsap.fromTo(chars, {
                'will-change': 'transform',
                xPercent: -250,
                rotationZ: 45,
                scaleX: 6,
                transformOrigin: '100% 50%'
            },
                {
                    duration: 1,
                    ease: 'power2',
                    xPercent: 0,
                    rotationZ: 0,
                    scaleX: 1,
                    stagger: 0.06,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        // scrub: true
                    }
                });
        });

        typographyFX_5S.forEach(title => {
            const chars = title.querySelectorAll('.char');
            wrapElements(chars, 'span', 'char-wrap');
            gsap.fromTo(chars, {
                'will-change': 'transform',
                xPercent: -250,
                rotationZ: 45,
                scaleX: 6,
                transformOrigin: '100% 50%'
            },
                {
                    duration: 1,
                    ease: 'power2',
                    xPercent: 0,
                    rotationZ: 0,
                    scaleX: 1,
                    stagger: 0.06,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        scrub: true
                    }
                });
        });

        typographyFX_6.forEach(title => {
            const chars = title.querySelectorAll('.char');
            chars.forEach(char => gsap.set(char.parentNode, { perspective: 2000 }));
            gsap.fromTo(chars, {
                'will-change': 'opacity, transform',
                opacity: 0,
                rotationY: 180,
                xPercent: -40,
                yPercent: 100
            },
                {
                    ease: 'power4.inOut()',
                    opacity: 1,
                    rotationY: 0,
                    xPercent: 0,
                    yPercent: 0,
                    stagger: {
                        each: 0.05,
                        from: 0
                    },
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        // scrub: 0.9
                    }
                });
        });

        typographyFX_6S.forEach(title => {
            const chars = title.querySelectorAll('.char');
            chars.forEach(char => gsap.set(char.parentNode, { perspective: 2000 }));
            gsap.fromTo(chars, {
                'will-change': 'opacity, transform',
                opacity: 0,
                rotationY: 180,
                xPercent: -40,
                yPercent: 100
            },
                {
                    ease: 'power4.inOut()',
                    opacity: 1,
                    rotationY: 0,
                    xPercent: 0,
                    yPercent: 0,
                    stagger: {
                        each: 0.05,
                        from: 0
                    },
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        scrub: 0.9
                    }
                });
        });

        typographyFX_7.forEach(title => {
            const words = [...title.querySelectorAll('.word')];
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: title,
                    start: 'center+=20% bottom',
                    end: '+=50%',
                    // scrub: true,
                    // pin: title.parentNode,
                }
            });
            for (const [wordPosition, word] of words.entries()) {
                tl.fromTo(word.querySelectorAll('.char'), {
                    'will-change': 'transform',
                    transformOrigin: () => !wordPosition%2 ? '50% 0%' : '50% 100%',
                    scaleY: 0,
                    filter: 'blur(24px) opacity(0)',
                },
                    {
                        ease: 'power1.inOut',
                        scaleY: 1,
                        filter: 'blur(0px) opacity(1)',
                        stagger: {
                            amount: 0.3,
                            from: 'left'
                        }
                    }, 0);
            }
        });

        typographyFX_7S.forEach(title => {
            const words = [...title.querySelectorAll('.word')];
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: title,
                    start: 'center+=20% bottom',
                    end: '+=50%',
                    scrub: true,
                    // pin: title.parentNode,
                }
            });
            for (const [wordPosition, word] of words.entries()) {
                tl.fromTo(word.querySelectorAll('.char'), {
                    'will-change': 'transform',
                    transformOrigin: () => !wordPosition%2 ? '50% 0%' : '50% 100%',
                    scaleY: 0,
                    filter: 'blur(24px) opacity(0)',
                },
                    {
                        ease: 'power1.inOut',
                        scaleY: 1,
                        filter: 'blur(0px) opacity(1)',
                        stagger: {
                            amount: 0.3,
                            from: 'left'
                        }
                    }, 0);
            }
        });

        typographyFX_8.forEach(title => {
            const chars = title.querySelectorAll('.char');
            gsap.fromTo(chars, {
                'will-change': 'opacity, transform',
                opacity: 0,
                x: 0,
                y: 50,
                z: -50,
                rotationX: -80,
                filter: 'blur(24px) opacity(0)',
                transformOrigin: '50% 0%'
            },
                {
                    opacity: 1,
                    x: 0,
                    y: 0,
                    z: 0,
                    rotationX: 0,
                    filter: 'blur(0px) opacity(1)',
                    ease: 'power4',
                    stagger: 0.03,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        // scrub: true
                    }
                });
        });

        typographyFX_8S.forEach(title => {
            const chars = title.querySelectorAll('.char');
            gsap.fromTo(chars, {
                'will-change': 'opacity, transform',
                opacity: 0,
                x: 0,
                y: 50,
                z: -50,
                rotationX: -80,
                filter: 'blur(24px) opacity(0)',
                transformOrigin: '50% 0%'
            },
                {
                    opacity: 1,
                    x: 0,
                    y: 0,
                    z: 0,
                    rotationX: 0,
                    filter: 'blur(0px) opacity(1)',
                    ease: 'power4',
                    stagger: 0.03,
                    scrollTrigger: {
                        trigger: title,
                        start: 'center+=20% bottom',
                        end: '+=50%',
                        scrub: true
                    }
                });
        });

    }


    function iconBoxAnimate() {
        const scroll = jQuery(window).scrollTop();
        const objectSelect = jQuery('.bw-iconbox-svg-animate');
        const bottom = jQuery(window).height();
        const objectPosition = objectSelect.offset().top - bottom;
        if (scroll > objectPosition) {
            jQuery('.bw-iconbox-svg-animate').addClass('run');
        } else {
            jQuery('.bw-iconbox-svg-animate').removeClass('run');
        }
    }

    function imageParallax() {
        const image = document.getElementsByClassName('bw-parallax');
        new simpleParallax(image);
    }

    function revealedText() {
        const $containers = jQuery('.bw-revealed-text');
        $containers.each(function () {
            const $container = jQuery(this);
            const factor = parseFloat($container.data('factor'));
            const $text = $container.find('p');
            let textContent = $text.text();
            $text.html('');

            const wordsAndSpaces = textContent.split(/(\s+)/);

            wordsAndSpaces.forEach((item) => {
                if (item.trim() !== '') {
                    const $span = jQuery('<span>').text(item);
                    $text.append($span);
                } else {
                    $text.append(' ');
                }
            });

            const $spans = $text.find('span');

            function handleScroll() {
                const maxScrollTop = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const currentScrollTop = document.documentElement.scrollTop;

                if (currentScrollTop >= maxScrollTop) {
                    $spans.css('opacity', 1);
                    return;
                }

                const scrolled = currentScrollTop / maxScrollTop * $spans.length * factor;
                $spans.each(function (index) {
                    if ((scrolled - index) > 1) {
                        jQuery(this).css('opacity', 1);
                    } else if ((scrolled - index) < 0.2) {
                        jQuery(this).css('opacity', 0.2);
                    } else {
                        jQuery(this).css('opacity', (scrolled - index));
                    }
                });
            }

            jQuery(document).on('scroll', handleScroll);
            handleScroll();
        });
    }

    function rotator() {
        const rotators = document.querySelectorAll('.bw-title-anime.bw-rotator');
        rotators.forEach(function(rotator) {
            const timeline = anime.timeline({ loop: true });

            const duration = parseFloat(rotator.dataset.duration);
            const delay = parseFloat(rotator.dataset.delay);

            // Clip
            const clipItems = rotator.querySelectorAll('.bw-rotator-clip .bw-rotator-item');
            const clipLine = rotator.querySelectorAll('.bw-rotator-clip .bw-line');
            clipItems.forEach(function(item) {
                const lineWidth = item.getBoundingClientRect().width;
                timeline.add({ targets: clipLine, translateX: [0, lineWidth], easing: "easeOutExpo", duration: duration });
                timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: duration }, `-=${duration}`);
                timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: delay, delay: duration });
                timeline.add({ targets: clipLine, translateX: [lineWidth, 0], easing: "easeOutExpo", duration: delay }, `-=${delay}`);
            });

            // Flip Rotate
            const frotateItems = rotator.querySelectorAll('.bw-rotator-frotate .bw-rotator-item');
            frotateItems.forEach(function(item) {
                timeline.add({ targets: item, rotateX: [-180, 0], easing:"easeOutExpo", duration: duration, delay: delay });
                timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: duration }, `-=${duration}`);
                timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
                timeline.add({ targets: item, rotateX: [0, 180], easing:"easeOutExpo", duration: duration }, `-=${duration}`);
            });

            // Latter FadeIn
            const lfinItems = rotator.querySelectorAll('.bw-rotator-lfin .bw-rotator-item');
            lfinItems.forEach(function(item) {
                const text = item.textContent;
                item.innerHTML = '';
                const letters = text.split('').map(letter => {
                    return `<span class="bw-letter">${letter}</span>`;
                }).join('');
                item.innerHTML = letters;
                const htmlLetters = item.querySelectorAll('.bw-letter');
                const singleDuration = duration / htmlLetters.length;
                timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 }, `+=${duration}`);
                htmlLetters.forEach(function(letter) {
                    timeline.add({ targets: letter, opacity: [0, 1], easing: "easeOutExpo", duration: singleDuration });
                });
                timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
            });

            // Latter FadeIn
            const lrotateItems = rotator.querySelectorAll('.bw-rotator-lrotate .bw-rotator-item');
            lrotateItems.forEach(function(item) {
                const text = item.textContent;
                item.innerHTML = '';
                const letters = text.split('').map(letter => {
                    return `<span class="bw-letter">${letter}</span>`;
                }).join('');
                item.innerHTML = letters;
                const htmlLetters = item.querySelectorAll('.bw-letter');
                const singleDuration = duration / htmlLetters.length;
                timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 }, `+=${duration}`);
                htmlLetters.forEach(function(letter) {
                    timeline.add({ targets: letter, opacity: [0, 1], rotateY:[180, 0], easing: "easeOutExpo", duration: singleDuration });
                });
                timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
            });

            // Typing Latter
            const typingItems = rotator.querySelectorAll('.bw-rotator-tlatter .bw-rotator-item');
            const typingLine = rotator.querySelectorAll('.bw-rotator-tlatter .bw-line');
            typingItems.forEach(function(item) {
                const lineWidth = item.getBoundingClientRect().width;
                timeline.add({ targets: typingLine, translateX: [0, lineWidth], easing: "easeOutExpo", duration: duration });
                timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: duration }, `-=${duration}`);
                timeline.add({ targets: item, background: 'rgba(0, 0, 0, 0.5)', easing: "easeOutExpo", duration: duration, delay: delay });
                timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: 0, delay: delay});
                timeline.add({ targets: typingLine, translateX: [lineWidth, 0], easing: "easeOutExpo", duration: duration }, `-=${delay}`);
            });

            // Bar Loading
            const bloadingItems = rotator.querySelectorAll('.bw-rotator-bloading .bw-rotator-item');
            const line = rotator.querySelector('.bw-rotator-bloading-line');
            bloadingItems.forEach(function(item) {
                const lineWidth = item.getBoundingClientRect().width;
                timeline.add({ targets: item, opacity: [0, 1], duration: 1 });
                timeline.add({ targets: line, width: [0, lineWidth], easing: "easeOutExpo", duration: duration });
                timeline.add({ targets: item, opacity: [1, 0], rotateX: [0, 100], easing: "easeOutExpo", duration: duration, delay: delay });
                timeline.add({ targets: line, width: [lineWidth, 0], easing: "easeOutExpo", duration: duration }, `-=${duration}`);
            });

            // Slide Top
            const stopItems = rotator.querySelectorAll('.bw-rotator-stop .bw-rotator-item');
            stopItems.forEach(function(item) {
                timeline.add({ targets: item, opacity: [0, 1], translateY: [-30, 5, 0], easing: "easeOutExpo", duration: duration, delay: delay });
                timeline.add({ targets: item, translateY: [0, 20], opacity: 0, easing: "easeOutExpo", duration: duration });
            });

            // Zoom Out
            const zoutItems = rotator.querySelectorAll('.bw-rotator-zout .bw-rotator-item');
            zoutItems.forEach(function(item) {
                timeline.add({ targets: item, opacity: [0, 1], scale: [1.5, 1], easing: "easeOutExpo", duration: duration });
                timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
            });

        // Scale In
        const sinItems = rotator.querySelectorAll('.bw-rotator-sin .bw-rotator-item');
        sinItems.forEach(function(item) {
            const text = item.textContent;
            item.innerHTML = '';
            const letters = text.split('').map(letter => {
                return `<span class="bw-letter">${letter}</span>`;
            }).join('');
            item.innerHTML = letters;
            const htmlLetters = item.querySelectorAll('.bw-letter');
            const singleDuration = duration / htmlLetters.length;
            timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 }, `+=${duration}`);
            htmlLetters.forEach(function(letter) {
                timeline.add({ targets: letter, scale:[0, 5, 1], opacity:[0, 1], easing: "easeOutExpo", duration: singleDuration });
            });
            timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
        });

        // Push Left
        const pleftItems = rotator.querySelectorAll('.bw-rotator-pleft .bw-rotator-item');
        pleftItems.forEach(function(item) {
            timeline.add({ targets: item, opacity: [0, 1], translateX: [-30, 0], easing: "easeOutExpo", duration: duration });
            timeline.add({ targets: item, opacity: 0, translateX: [0, 30], easing: "easeOutExpo", duration: duration, delay: delay });
        });

        // Color Effect
        const ceffectItems = rotator.querySelectorAll('.bw-rotator-ceffect .bw-rotator-item');
        ceffectItems.forEach(function(item) {
            timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 });
            timeline.add({ targets: item, backgroundPosition: ['200% 50%', '0% 50%'], easing: "easeOutExpo", duration: duration });
            timeline.add({ targets: item, opacity: 0, backgroundPosition: ['0% 50%', '200% 50%'], easing: "easeOutExpo", duration: 10, delay: delay });
        });

        // Bouncing Effect
        const beffectItems = rotator.querySelectorAll('.bw-rotator-beffect .bw-rotator-item');
        beffectItems.forEach(function(item) {
            const text = item.textContent;
            item.innerHTML = '';
            const letters = text.split('').map(letter => {
                return `<span class="bw-letter">${letter}</span>`;
            }).join('');
            item.innerHTML = letters;
            const htmlLetters = item.querySelectorAll('.bw-letter');
            const singleDuration = duration / htmlLetters.length;
            timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 }, `+=${duration}`);
            htmlLetters.forEach(function(letter) {
                timeline.add({ targets: letter, translateY: [-50, 35, -20, 0], opacity:[0, 1], easing: "easeOutExpo", duration: singleDuration });
            });
            timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
        });
    });
    }

    function svg() {
        const svgContainers = document.querySelectorAll('.bw-title-anime.bw-svg');
        svgContainers.forEach(function(container) {
            const loop = container.dataset.loop == 'yes' ? true : false;
            const delay = container.dataset.delay;
            const duration = container.dataset.duration;

            const timeline = anime.timeline({ loop: loop });

            const svg = container.querySelector('svg');
            const path = svg.querySelector('path');
            const length = path.getTotalLength();

            path.style.strokeDasharray = length;
            path.style.strokeDashoffset = length;
            timeline.add({ targets: path, strokeDashoffset: [length, 0], duration: duration, delay: delay, easing: 'easeOutExpo' });
        });
    }


    const observer = new MutationObserver(function(mutationsList) {
        mutationsList.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Ensure it's an element node
                    let $newElements = jQuery(node).find('.bw-button-box.modern.m-4 .btn-wrapper');
                    if ($newElements.length) {
                        applyTiltEffect($newElements); // Apply tilt effect to new elements
                    }

                    if (typeof setupScrollTrigger === 'function' ) {
                        $newElements = jQuery(node).find('.bw-section');
                        if ($newElements.length) {
                            setupScrollTrigger();
                        }
                    }

                    $newElements = jQuery(node).find('.bw-iconbox-svg-animate');
                    if ($newElements.length) {
                        iconBoxAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-parallax');
                    if ($newElements.length) {
                        imageParallax();
                    }

                    $newElements = jQuery(node).find('.bw-revealed-text');
                    if ($newElements.length) {
                        revealedText();
                    }

                    $newElements = jQuery(node).find('.bw-rotator');
                    if ($newElements.length) {
                        rotator();
                    }

                    $newElements = jQuery(node).find('.bw-svg');
                    if ($newElements.length) {
                        svg();
                    }

                    $newElements = jQuery(node).find('.bw-typograpgy-animate');
                    if ($newElements.length) {
                        typographyAnimate();
                    }
                }
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
});
