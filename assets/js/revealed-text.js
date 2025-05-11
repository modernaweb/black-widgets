jQuery(document).ready(function($) {
    const $containers = $('.bw-revealed-text');
    $containers.each(function () {
        const $container = $(this);
        const factor = parseFloat($container.data('factor'));
        const $text = $container.find('p');
        let textContent = $text.text();
        $text.html('');

        const wordsAndSpaces = textContent.split(/(\s+)/);

        wordsAndSpaces.forEach((item) => {
            if (item.trim() !== '') {
                const $span = $('<span>').text(item);
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
                    $(this).css('opacity', 1);
                } else if ((scrolled - index) < 0.2) {
                    $(this).css('opacity', 0.2);
                } else {
                    $(this).css('opacity', (scrolled - index));
                }
            });
        }

        $(document).on('scroll', handleScroll);
        handleScroll();
    });
});
