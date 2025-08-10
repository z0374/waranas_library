/**
 * Função genérica para controlar um slideshow.
 * @param {number} itemWidth - A largura de cada item do slide.
 * @param {number} last - O número total de slides.
 * @param {string} id - O ID do container dos slides.
 */
function slideshow(itemWidth, last, id) {
    slideshow.cnt = slideshow.cnt || {};
    slideshow.cnt[id] = slideshow.cnt[id] || 0;
    const container = document.getElementById(id);
    if (container) {
        container.scrollTo({
            left: itemWidth * slideshow.cnt[id],
            behavior: 'smooth'
        });
        slideshow.cnt[id] = (slideshow.cnt[id] + 1) % last;
    }
}