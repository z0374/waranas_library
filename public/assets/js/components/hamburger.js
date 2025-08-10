/**
 * Adiciona a funcionalidade de acordeão ao menu hambúrguer.
 * Garante que apenas um item <details> esteja aberto por vez.
 */
function setupHamburguer() {
    const hamburguerContainer = document.querySelector('.hamburguer');
    if (!hamburguerContainer) return;

    hamburguerContainer.addEventListener('click', (event) => {
        // Verifica se o alvo do clique foi o <summary>
        if (event.target.tagName === 'SUMMARY') {
            const clickedDetail = event.target.parentElement;

            // Encontra todos os elementos <details> dentro do contêiner
            const allDetails = hamburguerContainer.querySelectorAll('details');

            // Itera sobre todos e fecha os que não foram o clicado
            allDetails.forEach((detail) => {
                if (detail !== clickedDetail) {
                    detail.open = false;
                }
            });
        }
    });
}

// Inicializa a função assim que o script é carregado
setupHamburguer();