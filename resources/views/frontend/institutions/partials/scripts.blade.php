<script>
    function scrollCategories(amount) {
        document.getElementById('category-scroll').scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('category-scroll');
        const wrapper = document.getElementById('scroll-wrapper');
        if (!slider || !wrapper) return;

        let isDown = false;
        let startX;
        let scrollLeft;
        let isDragging = false;

        const updateIndicators = () => {
            const scrollWidth = slider.scrollWidth;
            const clientWidth = slider.clientWidth;
            const currentScroll = slider.scrollLeft;

            const btnLeft = document.getElementById('btn-left');
            const btnRight = document.getElementById('btn-right');

            const canScroll = scrollWidth > clientWidth + 5;

            if (!canScroll) {
                if (btnLeft) btnLeft.style.setProperty('display', 'none', 'important');
                if (btnRight) btnRight.style.setProperty('display', 'none', 'important');
                return;
            }

            const atLeft = currentScroll <= 15;
            const atRight = currentScroll >= (scrollWidth - clientWidth - 15);

            if (btnLeft) btnLeft.style.setProperty('display', atLeft ? 'none' : 'flex', atLeft ?
                'important' : '');
            if (btnRight) btnRight.style.setProperty('display', atRight ? 'none' : 'flex', atRight ?
                'important' : '');

            // Edge-fade toggling
            if (atLeft) wrapper.classList.remove('has-scroll-left');
            else wrapper.classList.add('has-scroll-left');

            if (atRight) wrapper.classList.remove('has-scroll-right');
            else wrapper.classList.add('has-scroll-right');
        };

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            isDragging = false;
            slider.classList.add('drag-active');
            startX = e.clientX;
            scrollLeft = slider.scrollLeft;
        });

        window.addEventListener('mouseup', () => {
            if (!isDown) return;
            isDown = false;
            slider.classList.remove('drag-active');
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            const x = e.clientX;
            const walk = (x - startX) * 2;
            if (Math.abs(walk) > 5) isDragging = true;
            slider.scrollLeft = scrollLeft - walk;
            updateIndicators();
        });

        slider.addEventListener('wheel', (e) => {
            // Only hijack the scroll if we're scrolling horizontally (or Shift is held)
            // or if the deltaX is significant. Otherwise, let it bubble up for page scroll.
            if (e.deltaX !== 0 || e.shiftKey) {
                e.preventDefault();
                slider.scrollLeft += (e.deltaX || e.deltaY);
                updateIndicators();
            }
        }, {
            passive: false
        });

        slider.addEventListener('click', (e) => {
            if (isDragging) {
                e.preventDefault();
                e.stopPropagation();
                isDragging = false;
            }
        }, true);

        slider.addEventListener('scroll', updateIndicators);
        window.addEventListener('resize', updateIndicators);
        setTimeout(updateIndicators, 200);
        slider.addEventListener('touchmove', updateIndicators, {
            passive: true
        });
    });
</script>
