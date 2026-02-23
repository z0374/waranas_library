function slideshow(elementId, dir) {
    const el = document.getElementById(elementId);
    if (!el || el.getAttribute('data-moving') === 'true') return;

    const slides = el.querySelectorAll('.slide-wrapper');
    if (slides.length <= 1) return;

    el.setAttribute('data-moving', 'true');
    
    // Tempo da transição (ajustado para ser rápido e fluido)
    const duration = 500; 

    if (dir === 1) {
        // --- MOVE PARA FRENTE ---
        el.style.transition = `transform ${duration}ms ease-in-out`;
        el.style.transform = "translateX(-100%)";

        setTimeout(() => {
            el.style.transition = "none";
            el.appendChild(slides[0]); // Move o que saiu para o final
            el.style.transform = "translateX(0%)";
            
            // Pequena pausa para o browser processar o 'none' antes de liberar
            setTimeout(() => el.setAttribute('data-moving', 'false'), 50);
        }, duration);
        
    } else {
        // --- MOVE PARA TRÁS ---
        // 1. Prepara o terreno: move o último para o início sem ninguém ver
        el.style.transition = "none";
        el.prepend(slides[slides.length - 1]);
        el.style.transform = "translateX(-100%)";

        // 2. Força o navegador a renderizar a nova posição (-100%)
        el.offsetHeight; 

        // 3. Anima de volta para o 0%
        el.style.transition = `transform ${duration}ms ease-in-out`;
        el.style.transform = "translateX(0%)";

        setTimeout(() => {
            el.setAttribute('data-moving', 'false');
        }, duration);
    }
}