(function($){
    function initBwSwiper($scope){
        const $carousels = $scope.find('.bw-swiper');
        $carousels.each(function(){
            const $this = $(this);

            if ($this.hasClass('swiper-initialized')) return;
            $this.addClass('swiper-initialized');

            // Slides per view, loop, autoplay
            const slidesPerView = parseInt($this.data('slides-per-view')) || 3;
            const slidesToScroll = parseInt($this.data('slides-to-scroll')) || 1;
            const autoplay = $this.data('autoplay') === true;
            const autoplaySpeed = parseInt($this.data('autoplay-speed')) || 3000;
            const loop = $this.data('loop') === true;
            const pauseOnHover = $this.data('pause-on-hover') === true;
            const pagination = $this.data('pagination') === true;
            const navigation = $this.data('navigation') === true;
            const type = $this.data('type');

            const swiperOptions = {
                slidesPerView: slidesPerView,
                slidesPerGroup: slidesToScroll,
                loop: loop,
                autoplay: autoplay ? { delay: autoplaySpeed, pauseOnMouseEnter: pauseOnHover, disableOnInteraction: false } : false,
                centeredSlides: true,
                spaceBetween: 10,
                parallax: true,
            };

            // Pagination
            if(pagination){
                swiperOptions.pagination = {
                    el: $this.find('.bw-swiper-pagination')[0],
                    clickable: true,
                    renderBullet: function(index, className){
                        const customDot = $this.data('custom-dot-icon');
                        if(type === 'type4'){
                            return `<span class="${className}"></span>`;
                        }
                        if(customDot){
                            return `<span class="${className}"><img src="${customDot}" alt="dot" /></span>`;
                        }
                        return `<span class="${className}"><svg width="24" height="24"><circle cx="12" cy="12" r="6" stroke-width="1"/></svg></span>`;
                    }
                };
            }

            // Navigation
            if(navigation){
                swiperOptions.navigation = {
                    nextEl: $this.find('.bw-swiper-button-next')[0],
                    prevEl: $this.find('.bw-swiper-button-prev')[0],
                };
            }

            // Type2 image width fix
            if($this.hasClass('bw-swiper-type2')){
                $this.find('.bw-swiper-mask').each(function(){
                    const $img = $(this).find('img').first();
                    const width = $img.outerWidth();
                    $(this).css('width', width + 'px');
                    $img.css('position','absolute');
                });
            }

            new Swiper($this[0], swiperOptions);
        });
    }

    jQuery(document).ready(function($) {
        initBwSwiper($(document));
    });

    $(window).on('elementor/frontend/init', function(){
        elementorFrontend.hooks.addAction('frontend/element_ready/b_image_carousel.default', initBwSwiper);
    });
})(jQuery);

