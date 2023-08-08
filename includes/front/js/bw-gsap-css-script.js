/**
 * Only Work when GSAP CDN is enabled...
 */

// select all elements with custom text
const elements = document.querySelectorAll('.bw-typograpgy-animate .word');

// loop through each element and split text into characters
elements.forEach((element) => {
  const text = element.textContent;
  const chars = text.split('');
  // replace text with span elements with custom class for each character
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
const scroll = () => {
    
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

};

window.addEventListener('load', scroll);