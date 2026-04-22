const quadroChat = document.getElementById('boardComponent');

    /**
     * Função para atualizar o quadro com novos dados (mensagens)
     * @param {string} conteudoHTML - O elemento ou texto a ser injetado
     */
    function atualizarQuadro(conteudoHTML) {
        
        // Adiciona ao quadro
        quadroChat.innerHTML += conteudoHTML;
        
        // Rola a barra de rolagem para o final para mostrar o item atualizado
        quadroChat.scrollTop = quadroChat.scrollHeight;
    }