const quadroChat = document.getElementById('boardComponent');

    /**
     * Função para atualizar o quadro com novos dados (mensagens)
     * @param {string} conteudoHTML - O elemento ou texto a ser injetado
     */
    function atualizarQuadro(conteudoHTML) {
        // Cria um contêiner temporário para a nova mensagem se ajustar ao grid
        const wrapperItem = document.createElement('div');
        wrapperItem.innerHTML = conteudoHTML;
        
        // Adiciona ao quadro
        quadroChat.appendChild(wrapperItem);
        
        // Rola a barra de rolagem para o final para mostrar o item atualizado
        quadroChat.scrollTop = quadroChat.scrollHeight;
    }