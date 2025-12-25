(function($){
    const titleAnimate = function($scope, $) {
        const scopeElement = $scope[0];
        // Simple
        const simpleTextWrappers = scopeElement.querySelectorAll('.bw-simple');
        const simpleTimeline = anime.timeline({loop: true});
        simpleTextWrappers.forEach(textWrapper => {
            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        simpleTimeline.add({ targets: '.bw-simple-wrap .bw-letter', scale: [3,1], opacity: [0,1], translateZ: 0, easing: "easeOutExpo", duration: 1379, delay: (el, i) => 70*i });
        simpleTimeline.add({ targets: '.bw-simple', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });

        // Classic
        const classicElements = scopeElement.querySelectorAll('.bw-classic');
        classicElements.forEach(classicElement => {
            const timeline = anime.timeline({ loop: true });
            if (!elementorFrontend.isEditMode()) {
                timeline.add({targets: classicElement.querySelectorAll('.bw-line'), opacity: [0.5, 1], scaleX: [0, 1], easing: "easeInOutExpo", duration: 700});
                timeline.add({targets: classicElement.querySelectorAll('.bw-line'), duration: 600, easing: "easeOutExpo", translateY: (el, i) => (-0.625 + 0.625 * 2 * i) + "em"});
                timeline.add({targets: classicElement.querySelectorAll('.bw-symbol'), opacity: [0, 1], scaleY: [0.5, 1], easing: "easeOutExpo", duration: 600, offset: '-=600'});
                timeline.add({targets: classicElement.querySelectorAll('.bw-before'), opacity: [0, 1], translateX: ["0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=300'});
                timeline.add({targets: classicElement.querySelectorAll('.bw-after'), opacity: [0, 1], translateX: ["-0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=600'});
            }else{
                timeline.add({targets: $('.bw-line').toArray(), opacity: [0.5, 1], scaleX: [0, 1], easing: "easeInOutExpo", duration: 700});
                timeline.add({targets: $('.bw-line').toArray(), duration: 600, easing: "easeOutExpo", translateY: (el, i) => (-0.625 + 0.625 * 2 * i) + "em"});
                timeline.add({targets: $('.bw-symbol').toArray(), opacity: [0, 1], scaleY: [0.5, 1], easing: "easeOutExpo", duration: 600, offset: '-=600'});
                timeline.add({targets: $('.bw-before').toArray(), opacity: [0, 1], translateX: ["0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=300'});
                timeline.add({targets: $('.bw-after').toArray(), opacity: [0, 1], translateX: ["-0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=600'});
            }
            timeline.add({targets: classicElement, opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000});
        });

        // Liner
        const linerTextWrappers = scopeElement.querySelectorAll('.bw-liner .bw-letters');
        linerTextWrappers.forEach(function(textWrapper) {
            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.timeline({loop: true})
            .add({ targets: '.bw-liner .bw-line', scaleX: [0, 1], opacity: [0.5, 1], easing: "easeInOutExpo", duration: 900 })
            .add({ targets: '.bw-liner .bw-letter', opacity: [0, 1], translateX: [40, 0], translateZ: 0, scaleX: [0.3, 1], easing: "easeOutExpo", duration: 800, offset: '-=600', delay: (el, i) => 150 + 25 * i })
            .add({ targets: '.bw-liner', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });

        // Effective
        const bwEffective = {};
        bwEffective.opacityIn = [0,1];
        bwEffective.scaleIn = [0.2, 1];
        bwEffective.scaleOut = 3;
        bwEffective.durationIn = 800;
        bwEffective.durationOut = 600;
        bwEffective.delay = 500;
        anime.timeline({loop: true})
            .add({ targets: '.bw-effective .bw-letters-1', opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
            .add({ targets: '.bw-effective .bw-letters-1', opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
            .add({ targets: '.bw-effective .bw-letters-2', opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
            .add({ targets: '.bw-effective .bw-letters-2', opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
            .add({ targets: '.bw-effective .bw-letters-3', opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
            .add({ targets: '.bw-effective .bw-letters-3', opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
            .add({ targets: '.bw-effective', opacity: 0, duration: 500, delay: 500 });

        // Typing
        const typingTextWrappers = scopeElement.querySelectorAll('.bw-typing .bw-letters');
        typingTextWrappers.forEach(function(typingTextWrapper) {
            typingTextWrapper.innerHTML = typingTextWrapper.textContent.replace(/([^\x00-\x80]|\w)/g, "<span class='bw-letter'>$&</span>");

            const lineWidth = typingTextWrapper.getBoundingClientRect().width + 10;

            anime.timeline({ loop: true })
                .add({ targets: typingTextWrapper.parentElement.querySelector('.bw-line'), scaleY: [0, 1], opacity: [0.5, 1], easing: "easeOutExpo", duration: 700 })
                .add({ targets: typingTextWrapper.parentElement.querySelector('.bw-line'), translateX: [0, lineWidth], easing: "easeOutExpo", duration: 700, delay: 600 })
                .add({ targets: typingTextWrapper.querySelectorAll('.bw-letter'), opacity: [0, 1], easing: "easeOutExpo", duration: 600, offset: '-=775', delay: (el, i) => 34 * (i + 1) })
                .add({ targets: typingTextWrapper.parentElement, opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });
        });

        // Fade from top
        const fftTextWrappers = scopeElement.querySelectorAll('.bw-fft .bw-letters');
        fftTextWrappers.forEach(function(fftTextWrapper) {
            fftTextWrapper.innerHTML = fftTextWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.timeline({loop: true})
            .add({ targets: '.bw-fft .bw-letter', translateY: [-90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
            .add({ targets: '.bw-fft .bw-letter', translateY: [0, 90], translateZ: 0, opacity: [1,0], easing: "easeInExpo", duration: 1200, delay: (el, i) => 500 + 30 * i });

        // Fade from bottom
        const ffbTextWrappers = scopeElement.querySelectorAll('.bw-ffb .bw-letters');
        ffbTextWrappers.forEach(function(ffbTextWrapper) {
            ffbTextWrapper.innerHTML = ffbTextWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.timeline({loop: true})
            .add({ targets: '.bw-ffb .bw-letter', translateY: [90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
            .add({ targets: '.bw-ffb .bw-letter', translateY: [0, -90], translateZ: 0, opacity: [1,0], easing: "easeInExpo", duration: 1200, delay: (el, i) => 500 + 30 * i });

        // Fade from left
        const fflTextWrappers = scopeElement.querySelectorAll('.bw-ffl .bw-letters');
        fflTextWrappers.forEach(function(fflTextWrapper) {
            fflTextWrapper.innerHTML = fflTextWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.timeline({loop: true})
            .add({ targets: '.bw-ffl .bw-letter', translateX: [-90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
            .add({ targets: '.bw-ffl .bw-letter', translateX: [0,90], opacity: [1,0], easing: "easeInExpo", duration: 1100, delay: (el, i) => 100 + 30 * i });

        // Fade from right
        const ffrTextWrappers = scopeElement.querySelectorAll('.bw-ffr .bw-letters');
        ffrTextWrappers.forEach(function(ffrTextWrapper) {
            ffrTextWrapper.innerHTML = ffrTextWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
        });
        anime.timeline({loop: true})
            .add({ targets: '.bw-ffr .bw-letter', translateX: [90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
            .add({ targets: '.bw-ffr .bw-letter', translateX: [0,-90], opacity: [1,0], easing: "easeInExpo", duration: 1100, delay: (el, i) => 100 + 30 * i });

        // Fade in
        anime.timeline({loop: true})
            .add({ targets: '.bw-fin .bw-word', scale: [3,1], opacity: [0,1], easing: "easeInOutExpo", duration: 800, delay: (el, i) => 800 * i })
            .add({ targets: '.bw-fin', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1200 });

        // Fade out
        anime.timeline({loop: true})
            .add({ targets: '.bw-fout', opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: 400, })
            .add({ targets: '.bw-fout .bw-word', opacity: 0, scale: [1,3], duration: 800, easing: "easeOutExpo", delay: (el, i) => 800 * i });


        // Rotator
        const rotators = scopeElement.querySelectorAll('.bw-title-anime.bw-rotator');
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

        // SVG
        const svgContainers = scopeElement.querySelectorAll('.bw-title-anime.bw-svg');
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

    };

    jQuery(document).ready(function () {
        titleAnimate(jQuery(document));
    });

    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/b_TitleAnimate.default',titleAnimate);
    });

})(jQuery);
