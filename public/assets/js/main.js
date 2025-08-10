/**
 * Adiciona um listener ao carregar a página para aplicar imagens de fundo
 * a elementos com a classe '.img' e o atributo 'data-bg'.
 * Este script é carregado em todas as páginas.
 */
window.addEventListener('DOMContentLoaded', () => {
    const backgrounds = document.querySelectorAll('.img');
    backgrounds.forEach(el => {
        const bgImage = el.dataset.bg;
        if (bgImage) {
            el.style.backgroundImage = bgImage;
            el.style.backgroundColor = 'rgba(255,255,255,0)';
        }
    });
});