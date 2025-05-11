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


    function simpleTitleAnimate() {
        const textWrappers = document.querySelectorAll('.bw-simple');
        textWrappers.forEach(textWrapper => {
            anime.remove(textWrapper);
            anime.remove(textWrapper.querySelectorAll('.bw-letter'));
            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.timeline({loop: true})
            .add({ targets: '.bw-simple-wrap .bw-letter', scale: [3,1], opacity: [0,1], translateZ: 0, easing: "easeOutExpo", duration: 1379, delay: (el, i) => 70*i })
            .add({ targets: '.bw-simple', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });
    }

    function classicTitleAnimate() {
        const classicElements = document.querySelectorAll('.bw-classic');
        classicElements.forEach(classicElement => {
            anime.remove(classicElement.querySelectorAll('.bw-line'));
            anime.remove(classicElement.querySelectorAll('.bw-symbol'));
            anime.remove(classicElement.querySelectorAll('.bw-before'));
            anime.remove(classicElement.querySelectorAll('.bw-after'));
            anime.remove(classicElement);
            const timeline = anime.timeline({ loop: true });

            timeline.add({targets: classicElement.querySelectorAll('.bw-line'), opacity: [0.5, 1], scaleX: [0, 1], easing: "easeInOutExpo", duration: 700});
            timeline.add({targets: classicElement.querySelectorAll('.bw-line'), duration: 600, easing: "easeOutExpo", translateY: (el, i) => (-0.625 + 0.625 * 2 * i) + "em"});
            timeline.add({targets: classicElement.querySelectorAll('.bw-symbol'), opacity: [0, 1], scaleY: [0.5, 1], easing: "easeOutExpo", duration: 600, offset: '-=600'});
            timeline.add({targets: classicElement.querySelectorAll('.bw-before'), opacity: [0, 1], translateX: ["0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=300'});
            timeline.add({targets: classicElement.querySelectorAll('.bw-after'), opacity: [0, 1], translateX: ["-0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=600'});
            timeline.add({targets: classicElement, opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000});
        });
    }

    function linerTitleAnimate() {
        const linerTextWrappers = document.querySelectorAll('.bw-liner .bw-letters');
        linerTextWrappers.forEach(function(textWrapper) {
            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.remove('.bw-liner');
        anime.remove('.bw-liner .bw-line');
        anime.remove('.bw-liner .bw-letter');
        anime.timeline({loop: true})
            .add({ targets: '.bw-liner .bw-line', scaleX: [0, 1], opacity: [0.5, 1], easing: "easeInOutExpo", duration: 900 })
            .add({ targets: '.bw-liner .bw-letter', opacity: [0, 1], translateX: [40, 0], translateZ: 0, scaleX: [0.3, 1], easing: "easeOutExpo", duration: 800, offset: '-=600', delay: (el, i) => 150 + 25 * i })
            .add({ targets: '.bw-liner', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });
    }

    function effectiveTitleAnimate() {
        const bwEffective = {};
        bwEffective.opacityIn = [0,1];
        bwEffective.scaleIn = [0.2, 1];
        bwEffective.scaleOut = 3;
        bwEffective.durationIn = 800;
        bwEffective.durationOut = 600;
        bwEffective.delay = 500;

        anime.remove('.bw-effective');
        anime.remove('.bw-effective .bw-letters-1');
        anime.remove('.bw-effective .bw-letters-2');
        anime.remove('.bw-effective .bw-letters-3');

        anime.timeline({loop: true})
            .add({ targets: '.bw-effective .bw-letters-1', opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
            .add({ targets: '.bw-effective .bw-letters-1', opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
            .add({ targets: '.bw-effective .bw-letters-2', opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
            .add({ targets: '.bw-effective .bw-letters-2', opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
            .add({ targets: '.bw-effective .bw-letters-3', opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
            .add({ targets: '.bw-effective .bw-letters-3', opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
            .add({ targets: '.bw-effective', opacity: 0, duration: 500, delay: 500 });
    }


    function typingTitleAnimate() {
        const typingTextWrappers = document.querySelectorAll('.bw-typing .bw-letters');
        typingTextWrappers.forEach(function(typingTextWrapper) {
            typingTextWrapper.innerHTML = typingTextWrapper.textContent.replace(/([^\x00-\x80]|\w)/g, "<span class='bw-letter'>$&</span>");

            const lineWidth = typingTextWrapper.getBoundingClientRect().width + 10;

            anime.remove(typingTextWrapper.parentElement);
            anime.remove(typingTextWrapper.querySelectorAll('.bw-letter'));
            anime.remove(typingTextWrapper.parentElement.querySelector('.bw-line'));

            anime.timeline({ loop: true })
                .add({ targets: typingTextWrapper.parentElement.querySelector('.bw-line'), scaleY: [0, 1], opacity: [0.5, 1], easing: "easeOutExpo", duration: 700 })
                .add({ targets: typingTextWrapper.parentElement.querySelector('.bw-line'), translateX: [0, lineWidth], easing: "easeOutExpo", duration: 700, delay: 600 })
                .add({ targets: typingTextWrapper.querySelectorAll('.bw-letter'), opacity: [0, 1], easing: "easeOutExpo", duration: 600, offset: '-=775', delay: (el, i) => 34 * (i + 1) })
                .add({ targets: typingTextWrapper.parentElement, opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });
        });
    }


    function fftTitleAnimate() {
        const fftTextWrappers = document.querySelectorAll('.bw-fft .bw-letters');
        fftTextWrappers.forEach(function(fftTextWrapper) {
            fftTextWrapper.innerHTML = fftTextWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.remove('bw-fft .bw-letter');
        anime.timeline({loop: true})
            .add({ targets: '.bw-fft .bw-letter', translateY: [-90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
            .add({ targets: '.bw-fft .bw-letter', translateY: [0, 90], translateZ: 0, opacity: [1,0], easing: "easeInExpo", duration: 1200, delay: (el, i) => 500 + 30 * i });
    }


    function ffbTitleAnimate() {
        const ffbTextWrappers = document.querySelectorAll('.bw-ffb .bw-letters');
        ffbTextWrappers.forEach(function(ffbTextWrapper) {
            ffbTextWrapper.innerHTML = ffbTextWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.remove('.bw-ffb .bw-letter');
        anime.timeline({loop: true})
            .add({ targets: '.bw-ffb .bw-letter', translateY: [90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
            .add({ targets: '.bw-ffb .bw-letter', translateY: [0, -90], translateZ: 0, opacity: [1,0], easing: "easeInExpo", duration: 1200, delay: (el, i) => 500 + 30 * i });
    }


    function fflTitleAnimate() {
        const fflTextWrappers = document.querySelectorAll('.bw-ffl .bw-letters');
        fflTextWrappers.forEach(function(fflTextWrapper) {
            fflTextWrapper.innerHTML = fflTextWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.remove('.bw-ffl .bw-letter');
        anime.timeline({loop: true})
            .add({ targets: '.bw-ffl .bw-letter', translateX: [-90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
            .add({ targets: '.bw-ffl .bw-letter', translateX: [0,90], opacity: [1,0], easing: "easeInExpo", duration: 1100, delay: (el, i) => 100 + 30 * i });
    }


    function ffrTitleAnimate() {
        const ffrTextWrappers = document.querySelectorAll('.bw-ffr .bw-letters');
        ffrTextWrappers.forEach(function(ffrTextWrapper) {
            ffrTextWrapper.innerHTML = ffrTextWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.remove('.bw-ffr .bw-letter');
        anime.timeline({loop: true})
            .add({ targets: '.bw-ffr .bw-letter', translateX: [90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
            .add({ targets: '.bw-ffr .bw-letter', translateX: [0,-90], opacity: [1,0], easing: "easeInExpo", duration: 1100, delay: (el, i) => 100 + 30 * i });
    }


    function finTitleAnimate() {
        anime.remove('.bw-fin');
        anime.remove('.bw-fin .bw-word');
        anime.timeline({loop: true})
            .add({ targets: '.bw-fin .bw-word', scale: [3,1], opacity: [0,1], easing: "easeInOutExpo", duration: 800, delay: (el, i) => 800 * i })
            .add({ targets: '.bw-fin', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1200 });
    }


    function foutTitleAnimate() {
        anime.remove('.bw-fout');
        anime.remove('.bw-fout .bw-word');
        anime.timeline({loop: true})
            .add({ targets: '.bw-fout', opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: 400, })
            .add({ targets: '.bw-fout .bw-word', opacity: 0, scale: [1,3], duration: 800, easing: "easeOutExpo", delay: (el, i) => 800 * i });
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


    function textMarquee() {
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
                        scrub: 0.5 
                    }
                });
            });
        }
    }

    
    function imageMarquee() {
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
    }


    function textAnimate() {
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
    }


    function imageCarousel() {
        const $carousels = jQuery('.bw-swiper');
        $carousels.each(function() {
            const $this = jQuery(this);

            if ($this.hasClass('bw-swiper-type3')) {
                new Swiper(this, {
                    centeredSlides: true,
                    slidesPerView: 1,
                    loop: true,
                    pagination: {
                        el: '.bw-swiper-pagination',
                        clickable: true,
                        renderBullet: function (index, className) {
                            return `<span class="${className}" tabindex="0" role="button" aria-label="Go to slide ${index}" aria-current="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="6" fill="none" stroke-width="1"/>
                        </svg>
                    </span>`;
                        }
                    },
                    navigation: {
                        nextEl: '.bw-swiper-button-next',
                        prevEl: '.bw-swiper-button-prev',
                    },
                });
            } else {
                new Swiper(this, {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                    parallax: true,
                    centeredSlides: true,
                    loop: true,
                    pagination: {
                        el: '.bw-swiper-pagination',
                        clickable: true,
                    },
                });
            }
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

                    $newElements = jQuery(node).find('.bw-simple');
                    if ($newElements.length) {
                        simpleTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-classic');
                    if ($newElements.length) {
                        classicTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-liner');
                    if ($newElements.length) {
                        linerTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-effective');
                    if ($newElements.length) {
                        effectiveTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-typing');
                    if ($newElements.length) {
                        typingTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-fft');
                    if ($newElements.length) {
                        fftTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-ffb');
                    if ($newElements.length) {
                        ffbTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-ffl');
                    if ($newElements.length) {
                        fflTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-ffr');
                    if ($newElements.length) {
                        ffrTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-fin');
                    if ($newElements.length) {
                        finTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-fout');
                    if ($newElements.length) {
                        foutTitleAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-revealed-text');
                    if ($newElements.length) {
                        revealedText();
                    }

                    $newElements = jQuery(node).find('.bw-text-marquee');
                    if ($newElements.length) {
                        textMarquee();
                    }

                    $newElements = jQuery(node).find('.bw-image-marquee');
                    if ($newElements.length) {
                        imageMarquee();
                    }

                    $newElements = jQuery(node).find('.bw-text-animate');
                    if ($newElements.length) {
                        textAnimate();
                    }

                    $newElements = jQuery(node).find('.bw-swiper');
                    if ($newElements.length) {
                        imageCarousel();
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
