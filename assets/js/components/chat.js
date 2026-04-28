/**
 * Envia a mensagem e processa a resposta
 */
async function sendMSG(textoDigitado) {
    // 1. Criamos o HTML como uma STRING (usando crases ``)
    const htmlUsuario = `
        <div class="chat-bubble user">
            <span class="bubble-text">${textoDigitado}</span>
        </div>`;
    
    atualizarQuadro(htmlUsuario);

    const chatContainer = document.querySelector('.chat-widget-container');
    const endpoint = chatContainer?.dataset.endpoint;

    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ciphertext: btoa(textoDigitado) })
        });

        // Se o entrypoint retornar HTML ou texto, pegamos como texto
        const data = await response.text();
        receivedMSG(data);

    } catch (error) {
        console.error("Erro no envio:", error);
        receivedMSG("Erro na comunicação com o servidor.");
    }
}

/**
 * Recebe e renderiza a resposta do sistema utilizando um template placeholder
 */
function receivedMSG(textoRecebido) {
    // 1. Captura o elemento de template (placeholder)
    const placeholder = document.querySelector('.placeholderbuble');
    
    if (placeholder) {
        // 2. Clona o elemento para não alterar o original que serve de base
        const novaBolha = placeholder.cloneNode(true);
        
        // 3. Remove a classe de placeholder para que ele se comporte como uma bolha normal (user/system)
        // E garante que ele esteja visível (caso o placeholder esteja com display:none no CSS)
        novaBolha.classList.remove('placeholderbuble');
        novaBolha.classList.add('system'); 
        novaBolha.style.display = 'block'; // Garante visibilidade se necessário

        // 4. Captura o HTML interno e substitui o marcador pelo texto recebido
        let conteudo = novaBolha.innerHTML;
        conteudo = conteudo.replace('{{message}}', textoRecebido);
        novaBolha.innerHTML = conteudo;

        // 5. Envia o elemento completo (outerHTML) para a função de atualização
        atualizarQuadro(novaBolha.outerHTML);
    } else {
        console.error("Template .placeholderbuble não encontrado no DOM.");
    }
}