/**
 * Lógica de Deslize Lateral Suave com Reinício Automático
 */
function slideshow(step, total, elementId, dir) {
    const container = document.getElementById(elementId);
    if (!container) return;

    // Obtém a posição atual (arredondada para evitar falhas de subpixel no Linux/Browser)
    const currentPos = Math.round(container.scrollLeft);
    const maxScroll = Math.round(step * (total - 1));
    
    // Calcula o próximo destino
    let targetPos = currentPos + (step * dir);

    // Lógica de Reinício (Loop)
    if (targetPos > maxScroll) {
        // Se ultrapassar o último slide, volta para o primeiro (0)
        targetPos = 0;
    } else if (targetPos < 0) {
        // Se tentar voltar antes do primeiro, vai para o último
        targetPos = maxScroll;
    }

    // Move suavemente para a posição calculada
    container.scrollTo({
        left: targetPos,
        behavior: 'smooth'
    });
}