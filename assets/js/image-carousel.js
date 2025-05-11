jQuery(document).ready(function() {
    const $carousels = jQuery('.bw-swiper');
    $carousels.each(function() {
        const $this = jQuery(this);
        if ($this.hasClass('bw-swiper-type2')) {
            $this.find('.bw-swiper-mask').each(function() {
                const $img = jQuery(jQuery(this).find('img')[0]);
                const width = $img.outerWidth();
                
                jQuery(this).css('width', `${width}px`);
                $img.css('position', 'absoulte');
            });
        } 

        if ($this.hasClass('bw-swiper-type3')) {
            new Swiper(this, {
                slidesPerView: 1,
                centeredSlides: true,
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
});

