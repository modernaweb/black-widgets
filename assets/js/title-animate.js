(function($){
    const API_KEY = 'bwTitleAnimateApi';

    const clearInlineAnimStyles = function(root) {
        if (!root) return;
        const nodes = [root].concat(Array.prototype.slice.call(root.querySelectorAll('*')));
        nodes.forEach(function(node) {
            if (!node || !node.style) return;
            node.style.removeProperty('opacity');
            node.style.removeProperty('transform');
            node.style.removeProperty('-webkit-transform');
            node.style.removeProperty('translate');
            node.style.removeProperty('scale');
            node.style.removeProperty('rotate');
            node.style.removeProperty('filter');
            node.style.removeProperty('background-position');
            // Keep strokeDash* for SVG until re-init rewrites them intentionally.
            if (node.tagName && node.tagName.toLowerCase() === 'path') {
                node.style.removeProperty('stroke-dashoffset');
                node.style.removeProperty('stroke-dasharray');
            }
        });
    };

    const destroyTitleAnimate = function($scope) {
        if (!$scope || !$scope.length) {
            return;
        }
        const el = $scope[0];
        const api = $scope.data(API_KEY);
        if (api && Array.isArray(api.timelines)) {
            api.timelines.forEach(function(tl) {
                try {
                    if (tl && typeof tl.pause === 'function') {
                        tl.pause();
                    }
                    if (tl && typeof tl.seek === 'function') {
                        tl.seek(0);
                    }
                } catch (e) {
                    // ignore timeline teardown errors
                }
            });
        }
        if (typeof anime !== 'undefined') {
            try {
                anime.remove(el);
                const nodes = el.querySelectorAll('*');
                if (nodes.length) {
                    anime.remove(nodes);
                }
            } catch (e) {
                // anime.remove may throw on empty targets in some builds
            }
        }
        clearInlineAnimStyles(el);
        $scope.removeData(API_KEY);
    };

    const fillLetterSpans = function(item) {
        if (!item) return;
        const text = item.textContent || '';
        const frag = document.createDocumentFragment();
        for (let i = 0; i < text.length; i++) {
            const span = document.createElement('span');
            span.className = 'bw-letter';
            span.textContent = text.charAt(i);
            frag.appendChild(span);
        }
        item.textContent = '';
        item.appendChild(frag);
    };

    const ensureLetterSpans = function(textWrapper) {
        if (!textWrapper) return;
        // Avoid nested spans on Elementor re-init.
        if (textWrapper.querySelector('.bw-letter')) return;
        const text = textWrapper.textContent || '';
        const frag = document.createDocumentFragment();
        for (let i = 0; i < text.length; i++) {
            const ch = text.charAt(i);
            if (/\S/.test(ch)) {
                const span = document.createElement('span');
                span.className = 'bw-letter';
                span.textContent = ch;
                frag.appendChild(span);
            } else {
                frag.appendChild(document.createTextNode(ch));
            }
        }
        textWrapper.textContent = '';
        textWrapper.appendChild(frag);
    };

    const safeNumber = function(value, fallback) {
        const n = parseFloat(value);
        return Number.isFinite(n) ? n : fallback;
    };

    const titleAnimate = function($scope, $) {
        const scopeElement = $scope[0];
        if (!scopeElement || typeof anime === 'undefined') {
            return;
        }

        destroyTitleAnimate($scope);

        const timelines = [];

        const getAnimMeta = (element) => {
            const isLooping = !!(element && element.dataset.loop === 'yes');
            let speed = safeNumber(element && element.dataset.speed, 1);
            if (!Number.isFinite(speed) || speed <= 0) {
                speed = 1;
            }
            speed = Math.min(3, Math.max(0.25, speed));

            const scaleTime = (value) => {
                if (typeof value !== 'number' || !Number.isFinite(value)) {
                    return value;
                }
                // Keep near-zero timings near-zero so instant steps stay instant.
                if (Math.abs(value) < 1) {
                    return value;
                }
                return Math.max(1, Math.round(value / speed));
            };

            const scaleOffset = (offset) => {
                if (typeof offset === 'number') {
                    return scaleTime(offset);
                }
                if (typeof offset === 'string') {
                    const match = offset.match(/^([+\-]=)(-?\d+(?:\.\d+)?)$/);
                    if (match) {
                        return match[1] + String(scaleTime(parseFloat(match[2])));
                    }
                }
                return offset;
            };

            const createTimeline = (opts) => {
                const timeline = anime.timeline(Object.assign({ loop: isLooping }, opts || {}));
                const originalAdd = timeline.add.bind(timeline);
                timeline.add = function(params, offset) {
                    if (params && typeof params === 'object' && !Array.isArray(params)) {
                        const next = Object.assign({}, params);
                        if (typeof next.duration === 'number') {
                            next.duration = scaleTime(next.duration);
                        }
                        if (typeof next.delay === 'number') {
                            next.delay = scaleTime(next.delay);
                        } else if (typeof next.delay === 'function') {
                            const delayFn = next.delay;
                            next.delay = function(el, i, l) {
                                return scaleTime(delayFn(el, i, l));
                            };
                        }
                        if (typeof next.offset === 'string' || typeof next.offset === 'number') {
                            next.offset = scaleOffset(next.offset);
                        }
                        return originalAdd(next, scaleOffset(offset));
                    }
                    return originalAdd(params, scaleOffset(offset));
                };
                timelines.push(timeline);
                return timeline;
            };

            return { isLooping, speed, scaleTime, createTimeline };
        };

        const simpleWrappers = scopeElement.querySelectorAll('.bw-title-anime.bw-simple-wrap');
        
        simpleWrappers.forEach(simpleWrapper => {
            const textWrapper = simpleWrapper.querySelector('.bw-simple');
            if (!textWrapper) return;

            ensureLetterSpans(textWrapper);

            const { isLooping, createTimeline } = getAnimMeta(simpleWrapper);
            const simpleTimeline = createTimeline();
            
            simpleTimeline.add({
                targets: $(textWrapper).find('.bw-letter').toArray(),
                scale: [3, 1],
                opacity: [0, 1],
                translateZ: 0,
                easing: "easeOutExpo",
                duration: 1379,
                delay: (el, i) => 70 * i
            });
            
            if (isLooping) {
                simpleTimeline.add({
                    targets: textWrapper,
                    opacity: 0,
                    duration: 1000,
                    easing: "easeOutExpo",
                    delay: 1000
                });
            }
        });

        // Classic
        const classicElements = scopeElement.querySelectorAll('.bw-classic');
        classicElements.forEach(classicElement => {
            const { isLooping, createTimeline } = getAnimMeta(classicElement);
            const timeline = createTimeline();
            
            const $ce = $(classicElement);
            
            timeline.add({targets: $ce.find('.bw-line').toArray(), opacity: [0.5, 1], scaleX: [0, 1], easing: "easeInOutExpo", duration: 700});
            timeline.add({targets: $ce.find('.bw-line').toArray(), duration: 600, easing: "easeOutExpo", translateY: (el, i) => (-0.625 + 0.625 * 2 * i) + "em"});
            timeline.add({targets: $ce.find('.bw-symbol').toArray(), opacity: [0, 1], scaleY: [0.5, 1], easing: "easeOutExpo", duration: 600, offset: '-=600'});
            timeline.add({targets: $ce.find('.bw-before').toArray(), opacity: [0, 1], translateX: ["0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=300'});
            timeline.add({targets: $ce.find('.bw-after').toArray(), opacity: [0, 1], translateX: ["-0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=600'});
            
            if (isLooping) {
                timeline.add({targets: classicElement, opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000});
            }
        });

        // Liner
        const linerElements = scopeElement.querySelectorAll('.bw-liner');
        linerElements.forEach(linerElement => {
            const { isLooping, createTimeline } = getAnimMeta(linerElement);
            
            const textWrapper = linerElement.querySelector('.bw-letters');
            ensureLetterSpans(textWrapper);

            const $liner = $(linerElement);
            const timeline = createTimeline();

            timeline
                .add({ 
                    targets: $liner.find('.bw-line').toArray(), 
                    scaleX: [0, 1], 
                    opacity: [0.5, 1], 
                    easing: "easeInOutExpo", 
                    duration: 900 
                })
                .add({ 
                    targets: $liner.find('.bw-letter').toArray(), 
                    opacity: [0, 1], 
                    translateX: [40, 0], 
                    translateZ: 0, 
                    scaleX: [0.3, 1], 
                    easing: "easeOutExpo", 
                    duration: 800, 
                    offset: '-=600', 
                    delay: (el, i) => 150 + 25 * i 
                });

            if (isLooping) {
                timeline.add({ 
                    targets: linerElement, 
                    opacity: 0, 
                    duration: 1000, 
                    easing: "easeOutExpo", 
                    delay: 1000 
                });
            }
        });

        // Effective
        const effectiveElements = scopeElement.querySelectorAll('.bw-effective');
        const bwEffective = {
            opacityIn: [0, 1],
            scaleIn: [0.2, 1],
            scaleOut: 3,
            durationIn: 800,
            durationOut: 600,
            delay: 500
        };

        effectiveElements.forEach(effectiveElement => {
            const { isLooping, createTimeline } = getAnimMeta(effectiveElement);
            const $eff = $(effectiveElement);
            const timeline = createTimeline();

            timeline
                .add({ targets: $eff.find('.bw-letters-1').toArray(), opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
                .add({ targets: $eff.find('.bw-letters-1').toArray(), opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
                
                .add({ targets: $eff.find('.bw-letters-2').toArray(), opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
                .add({ targets: $eff.find('.bw-letters-2').toArray(), opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
                
                .add({ targets: $eff.find('.bw-letters-3').toArray(), opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn });

            if (isLooping) {
                timeline
                    .add({ targets: $eff.find('.bw-letters-3').toArray(), opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
                    .add({ targets: effectiveElement, opacity: 0, duration: 500, delay: 500 });
            }
        });

        // Fade from top
        const fftElements = scopeElement.querySelectorAll('.bw-fft');

        fftElements.forEach(fftElement => {
            ensureLetterSpans(fftElement.querySelector('.bw-letters'));

            const { isLooping, createTimeline } = getAnimMeta(fftElement);
            const $fft = $(fftElement);
            const timeline = createTimeline();

            timeline.add({ 
                targets: $fft.find('.bw-letter').toArray(), 
                translateY: [-90, 0], 
                translateZ: 0, 
                opacity: [0, 1], 
                easing: "easeOutExpo", 
                duration: 1200, 
                delay: (el, i) => 500 + 30 * i 
            });

            if (isLooping) {
                timeline.add({ 
                    targets: $fft.find('.bw-letter').toArray(), 
                    translateY: [0, 90], 
                    translateZ: 0, 
                    opacity: [1, 0], 
                    easing: "easeInExpo", 
                    duration: 1200, 
                    delay: (el, i) => 500 + 30 * i 
                });
            }
        });

        // Fade from bottom
        const ffbElements = scopeElement.querySelectorAll('.bw-ffb');

        ffbElements.forEach(ffbElement => {
            ensureLetterSpans(ffbElement.querySelector('.bw-letters'));

            const { isLooping, createTimeline } = getAnimMeta(ffbElement);
            const $ffb = $(ffbElement);
            const timeline = createTimeline();

            timeline.add({ 
                targets: $ffb.find('.bw-letter').toArray(), 
                translateY: [90, 0], 
                translateZ: 0, 
                opacity: [0, 1], 
                easing: "easeOutExpo", 
                duration: 1200, 
                delay: (el, i) => 500 + 30 * i 
            });

            if (isLooping) {
                timeline.add({ 
                    targets: $ffb.find('.bw-letter').toArray(), 
                    translateY: [0, -90], 
                    translateZ: 0, 
                    opacity: [1, 0], 
                    easing: "easeInExpo", 
                    duration: 1200, 
                    delay: (el, i) => 500 + 30 * i 
                });
            }
        });

        // Fade from left
        const fflElements = scopeElement.querySelectorAll('.bw-ffl');

        fflElements.forEach(fflElement => {
            ensureLetterSpans(fflElement.querySelector('.bw-letters'));

            const { isLooping, createTimeline } = getAnimMeta(fflElement);
            const $ffl = $(fflElement);
            const timeline = createTimeline();

            timeline.add({ 
                targets: $ffl.find('.bw-letter').toArray(), 
                translateX: [-90, 0], 
                translateZ: 0, 
                opacity: [0, 1], 
                easing: "easeOutExpo", 
                duration: 1200, 
                delay: (el, i) => 500 + 30 * i 
            });

            if (isLooping) {
                timeline.add({ 
                    targets: $ffl.find('.bw-letter').toArray(), 
                    translateX: [0, 90], 
                    opacity: [1, 0], 
                    easing: "easeInExpo", 
                    duration: 1100, 
                    delay: (el, i) => 100 + 30 * i 
                });
            }
        });

        // Fade from right
        const ffrElements = scopeElement.querySelectorAll('.bw-ffr');

        ffrElements.forEach(ffrElement => {
            ensureLetterSpans(ffrElement.querySelector('.bw-letters'));

            const { isLooping, createTimeline } = getAnimMeta(ffrElement);
            const $ffr = $(ffrElement);
            const timeline = createTimeline();

            timeline.add({ 
                targets: $ffr.find('.bw-letter').toArray(), 
                translateX: [90, 0], 
                translateZ: 0, 
                opacity: [0, 1], 
                easing: "easeOutExpo", 
                duration: 1200, 
                delay: (el, i) => 500 + 30 * i 
            });

            if (isLooping) {
                timeline.add({ 
                    targets: $ffr.find('.bw-letter').toArray(), 
                    translateX: [0, -90], 
                    opacity: [1, 0], 
                    easing: "easeInExpo", 
                    duration: 1100, 
                    delay: (el, i) => 100 + 30 * i 
                });
            }
        });

        // Fade in
        const finElements = scopeElement.querySelectorAll('.bw-fin');
        finElements.forEach(finElement => {
            const { isLooping, createTimeline } = getAnimMeta(finElement);
            const $fin = $(finElement);
            const timeline = createTimeline();

            timeline.add({
                targets: $fin.find('.bw-word').toArray(),
                scale: [3, 1],
                opacity: [0, 1],
                easing: "easeInOutExpo",
                duration: 800,
                delay: (el, i) => 800 * i
            });

            if (isLooping) {
                timeline.add({
                    targets: finElement,
                    opacity: 0,
                    duration: 1000,
                    easing: "easeOutExpo",
                    delay: 1200
                });
            }
        });

        // Fade out
        const foutElements = scopeElement.querySelectorAll('.bw-fout');
        foutElements.forEach(foutElement => {
            const { createTimeline } = getAnimMeta(foutElement);
            const $fout = $(foutElement);
            const timeline = createTimeline();

            timeline
                .add({ 
                    targets: foutElement, 
                    opacity: [0, 1], 
                    easing: "easeOutExpo", 
                    duration: 1200, 
                    delay: 400 
                })
                .add({ 
                    targets: $fout.find('.bw-word').toArray(), 
                    opacity: 0, 
                    scale: [1, 3], 
                    duration: 800, 
                    easing: "easeOutExpo", 
                    delay: (el, i) => 800 * i 
                });
        });


        // Rotator
        const rotators = scopeElement.querySelectorAll('.bw-title-anime.bw-rotator');
        rotators.forEach(function(rotator) {
            const { isLooping, createTimeline } = getAnimMeta(rotator);
            const timeline = createTimeline();
            const $rotator = $(rotator);

            const duration = safeNumber(rotator.dataset.duration, 700);
            const delay = safeNumber(rotator.dataset.delay, 1000);

            // Clip
            const clipItems = rotator.querySelectorAll('.bw-rotator-clip .bw-rotator-item');
            if (clipItems.length > 0) {
                const clipLine = $rotator.find('.bw-rotator-clip .bw-line').toArray();
                clipItems.forEach(function(item, index) {
                    const lineWidth = item.getBoundingClientRect().width;
                    
                    // In
                    timeline.add({ targets: clipLine, translateX: [0, lineWidth], easing: "easeOutExpo", duration: duration });
                    timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: duration }, `-=${duration}`);
                    
                    // Out (Skip if last item and not looping)
                    if (isLooping || index < clipItems.length - 1) {
                        timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: delay, delay: duration });
                        timeline.add({ targets: clipLine, translateX: [lineWidth, 0], easing: "easeOutExpo", duration: delay }, `-=${delay}`);
                    }
                });
            }

            // Flip Rotate
            const frotateItems = rotator.querySelectorAll('.bw-rotator-frotate .bw-rotator-item');
            if (frotateItems.length > 0) {
                frotateItems.forEach(function(item, index) {
                    // In
                    timeline.add({ targets: item, rotateX: [-180, 0], easing:"easeOutExpo", duration: duration, delay: delay });
                    timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: duration }, `-=${duration}`);
                    
                    // Out
                    if (isLooping || index < frotateItems.length - 1) {
                        timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
                        timeline.add({ targets: item, rotateX: [0, 180], easing:"easeOutExpo", duration: duration }, `-=${duration}`);
                    }
                });
            }

            // Latter FadeIn
            const lfinItems = rotator.querySelectorAll('.bw-rotator-lfin .bw-rotator-item');
            if (lfinItems.length > 0) {
                lfinItems.forEach(function(item, index) {
                    fillLetterSpans(item);
                    
                    const htmlLetters = item.querySelectorAll('.bw-letter');
                    const singleDuration = duration / htmlLetters.length;
                    
                    // In
                    timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 }, `+=${duration}`);
                    htmlLetters.forEach(function(letter) {
                        timeline.add({ targets: letter, opacity: [0, 1], easing: "easeOutExpo", duration: singleDuration });
                    });
                    
                    // Out
                    if (isLooping || index < lfinItems.length - 1) {
                        timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
                    }
                });
            }

            // Latter FadeIn 2 (Rotate)
            const lrotateItems = rotator.querySelectorAll('.bw-rotator-lrotate .bw-rotator-item');
            if (lrotateItems.length > 0) {
                lrotateItems.forEach(function(item, index) {
                    fillLetterSpans(item);
                    
                    const htmlLetters = item.querySelectorAll('.bw-letter');
                    const singleDuration = duration / htmlLetters.length;
                    
                    // In
                    timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 }, `+=${duration}`);
                    htmlLetters.forEach(function(letter) {
                        timeline.add({ targets: letter, opacity: [0, 1], rotateY:[180, 0], easing: "easeOutExpo", duration: singleDuration });
                    });
                    
                    // Out
                    if (isLooping || index < lrotateItems.length - 1) {
                        timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
                    }
                });
            }

            // Typing Latter
            const typingItems = rotator.querySelectorAll('.bw-rotator-tlatter .bw-rotator-item');
            if (typingItems.length > 0) {
                const typingLine = $rotator.find('.bw-rotator-tlatter .bw-line').toArray();
                typingItems.forEach(function(item, index) {
                    const lineWidth = item.getBoundingClientRect().width;
                    
                    // In
                    timeline.add({ targets: typingLine, translateX: [0, lineWidth], easing: "easeOutExpo", duration: duration });
                    timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: duration }, `-=${duration}`);
                    
                    // Out
                    if (isLooping || index < typingItems.length - 1) {
                        timeline.add({ targets: item, background: 'rgba(0, 0, 0, 0.5)', easing: "easeOutExpo", duration: duration, delay: delay });
                        timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: 0, delay: delay});
                        timeline.add({ targets: typingLine, translateX: [lineWidth, 0], easing: "easeOutExpo", duration: duration }, `-=${delay}`);
                    }
                });
            }

            // Bar Loading
            const bloadingItems = rotator.querySelectorAll('.bw-rotator-bloading .bw-rotator-item');
            if (bloadingItems.length > 0) {
                const line = $rotator.find('.bw-rotator-bloading-line').toArray();
                bloadingItems.forEach(function(item, index) {
                    const lineWidth = item.getBoundingClientRect().width;
                    
                    // In
                    timeline.add({ targets: item, opacity: [0, 1], duration: 1 });
                    timeline.add({ targets: line, width: [0, lineWidth], easing: "easeOutExpo", duration: duration });
                    
                    // Out
                    if (isLooping || index < bloadingItems.length - 1) {
                        timeline.add({ targets: item, opacity: [1, 0], rotateX: [0, 100], easing: "easeOutExpo", duration: duration, delay: delay });
                        timeline.add({ targets: line, width: [lineWidth, 0], easing: "easeOutExpo", duration: duration }, `-=${duration}`);
                    }
                });
            }

            // Slide Top
            const stopItems = rotator.querySelectorAll('.bw-rotator-stop .bw-rotator-item');
            if (stopItems.length > 0) {
                stopItems.forEach(function(item, index) {
                    // In
                    timeline.add({ targets: item, opacity: [0, 1], translateY: [-30, 5, 0], easing: "easeOutExpo", duration: duration, delay: delay });
                    
                    // Out
                    if (isLooping || index < stopItems.length - 1) {
                        timeline.add({ targets: item, translateY: [0, 20], opacity: 0, easing: "easeOutExpo", duration: duration });
                    }
                });
            }

            // Zoom Out
            const zoutItems = rotator.querySelectorAll('.bw-rotator-zout .bw-rotator-item');
            if (zoutItems.length > 0) {
                zoutItems.forEach(function(item, index) {
                    // In
                    timeline.add({ targets: item, opacity: [0, 1], scale: [1.5, 1], easing: "easeOutExpo", duration: duration });
                    
                    // Out
                    if (isLooping || index < zoutItems.length - 1) {
                        timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
                    }
                });
            }

            // Scale In
            const sinItems = rotator.querySelectorAll('.bw-rotator-sin .bw-rotator-item');
            if (sinItems.length > 0) {
                sinItems.forEach(function(item, index) {
                    fillLetterSpans(item);
                    
                    const htmlLetters = item.querySelectorAll('.bw-letter');
                    const singleDuration = duration / htmlLetters.length;
                    
                    // In
                    timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 }, `+=${duration}`);
                    htmlLetters.forEach(function(letter) {
                        timeline.add({ targets: letter, scale:[0, 5, 1], opacity:[0, 1], easing: "easeOutExpo", duration: singleDuration });
                    });
                    
                    // Out
                    if (isLooping || index < sinItems.length - 1) {
                        timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
                    }
                });
            }

            // Push Left
            const pleftItems = rotator.querySelectorAll('.bw-rotator-pleft .bw-rotator-item');
            if (pleftItems.length > 0) {
                pleftItems.forEach(function(item, index) {
                    // In
                    timeline.add({ targets: item, opacity: [0, 1], translateX: [-30, 0], easing: "easeOutExpo", duration: duration });
                    
                    // Out
                    if (isLooping || index < pleftItems.length - 1) {
                        timeline.add({ targets: item, opacity: 0, translateX: [0, 30], easing: "easeOutExpo", duration: duration, delay: delay });
                    }
                });
            }

            // Color Effect
            const ceffectItems = rotator.querySelectorAll('.bw-rotator-ceffect .bw-rotator-item');
            if (ceffectItems.length > 0) {
                ceffectItems.forEach(function(item, index) {
                    // In
                    timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 });
                    timeline.add({ targets: item, backgroundPosition: ['200% 50%', '0% 50%'], easing: "easeOutExpo", duration: duration });
                    
                    // Out
                    if (isLooping || index < ceffectItems.length - 1) {
                        timeline.add({ targets: item, opacity: 0, backgroundPosition: ['0% 50%', '200% 50%'], easing: "easeOutExpo", duration: 10, delay: delay });
                    }
                });
            }

            // Bouncing Effect
            const beffectItems = rotator.querySelectorAll('.bw-rotator-beffect .bw-rotator-item');
            if (beffectItems.length > 0) {
                beffectItems.forEach(function(item, index) {
                    fillLetterSpans(item);
                    
                    const htmlLetters = item.querySelectorAll('.bw-letter');
                    const singleDuration = duration / htmlLetters.length;
                    
                    // In
                    timeline.add({ targets: item, opacity: [0, 1], easing: "easeOutExpo", duration: 10 }, `+=${duration}`);
                    htmlLetters.forEach(function(letter) {
                        timeline.add({ targets: letter, translateY: [-50, 35, -20, 0], opacity:[0, 1], easing: "easeOutExpo", duration: singleDuration });
                    });
                    
                    // Out
                    if (isLooping || index < beffectItems.length - 1) {
                        timeline.add({ targets: item, opacity: 0, easing: "easeOutExpo", duration: duration, delay: delay });
                    }
                });
            }
        });

        // Typing
        const typingElements = scopeElement.querySelectorAll('.bw-typing');
        typingElements.forEach(function(typingElement) {
            const lettersEl = typingElement.querySelector('.bw-letters');
            ensureLetterSpans(lettersEl);

            const { isLooping, createTimeline } = getAnimMeta(typingElement);
            const $typing = $(typingElement);
            const timeline = createTimeline();
            const lettersWidth = lettersEl ? (lettersEl.getBoundingClientRect().width + 10) : 0;

            timeline
                .add({
                    targets: $typing.find('.bw-line').toArray(),
                    scaleY: [0, 1],
                    opacity: [0.5, 1],
                    easing: "easeOutExpo",
                    duration: 700
                })
                .add({
                    targets: $typing.find('.bw-line').toArray(),
                    translateX: [0, lettersWidth],
                    easing: "easeOutExpo",
                    duration: 700,
                    delay: 600
                })
                .add({
                    targets: $typing.find('.bw-letter').toArray(),
                    opacity: [0, 1],
                    easing: "easeOutExpo",
                    duration: 600,
                    offset: '-=775',
                    delay: (el, i) => 34 * (i + 1)
                });

            if (isLooping) {
                timeline.add({
                    targets: typingElement,
                    opacity: 0,
                    duration: 1000,
                    easing: "easeOutExpo",
                    delay: 1000
                });
            }
        });

        // SVG
        const svgContainers = scopeElement.querySelectorAll('.bw-title-anime.bw-svg');
        svgContainers.forEach(function(container) {
            const { createTimeline, isLooping } = getAnimMeta(container);
            // Align with PHP defaults: duration 4000, delay 2000 (hold before reset when looping).
            const delay = safeNumber(container.dataset.delay, 2000);
            const duration = safeNumber(container.dataset.duration, 4000);

            const svg = container.querySelector('svg');
            if (!svg) {
                return;
            }
            const path = svg.querySelector('path');
            if (!path || typeof path.getTotalLength !== 'function') {
                return;
            }

            const length = path.getTotalLength();
            if (!Number.isFinite(length) || length <= 0) {
                return;
            }

            const timeline = createTimeline();
            path.style.strokeDasharray = length;
            path.style.strokeDashoffset = length;
            timeline.add({ targets: path, strokeDashoffset: [length, 0], duration: duration, delay: 0, easing: 'easeOutExpo' });
            if (isLooping) {
                // Hold drawn state, then snap back so the loop is not an instant reset.
                timeline.add({ targets: path, strokeDashoffset: length, duration: 1, delay: delay, easing: 'linear' });
            }
        });

        $scope.data(API_KEY, { initialized: true, timelines: timelines });
    };

    // Shared entry for Elementor preview MutationObserver (avoid double-init races).
    window.bwInitTitleAnimate = function($scope) {
        if (!$scope || !$scope.jquery) {
            $scope = jQuery($scope);
        }
        titleAnimate($scope, jQuery);
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/b_TitleAnimate.default', titleAnimate);
    });

})(jQuery);
