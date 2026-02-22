/**
 * Move o slide usando Transform Translate para efeito Ease
 */
function slideshow(elementId, dir) {
    const el = document.getElementById(elementId);
    if (!el) return;

    const slides = el.querySelectorAll('.slide-wrapper');
    const totalSlides = slides.length;

    // Recupera ou define o índice atual usando um atributo de dados no elemento
    let currentIndex = parseInt(el.getAttribute('data-index') || '0');

    // Calcula o próximo índice
    currentIndex += dir;

    // Lógica de Loop Infinito
    if (currentIndex >= totalSlides) {
        currentIndex = 0;
    } else if (currentIndex < 0) {
        currentIndex = totalSlides - 1;
    }

    // Salva o novo índice
    el.setAttribute('data-index', currentIndex);

    // Aplica o movimento de deslize (100% por slide)
    const offset = currentIndex * -100;
    el.style.transform = `translateX(${offset}%)`;
}