<?php

/**
 * Renderiza o componente da barra de digitação do chat utilizando um formulário.
 *
 * @param string $action      A ação a ser executada (nome da função JS ou URL do endpoint).
 * @param string $actionType  O tipo de ação ('function' ou 'endpoint'). Padrão: 'function'.
 * @param string $placeholder O texto de dica dentro do campo de input.
 * @param string $buttonText  O texto do botão de envio.
 * @return string             O HTML e JS renderizados do componente.
 */
function inputSubmit($action, $actionType = 'function', $placeholder = 'Digite a sua mensagem...', $buttonText = 'Enviar') {
    
    global $css_files, $script;

    // Tratamento de segurança (XSS)
    $safeAction      = htmlspecialchars($action, ENT_QUOTES, 'UTF-8');
    $safeActionType  = htmlspecialchars($actionType, ENT_QUOTES, 'UTF-8');
    $safePlaceholder = htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8');
    $safeButtonText  = htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8');

    // Gera IDs únicos 
    $uniqueId = uniqid('chat_');
    $formId   = $uniqueId . '_form';
    $inputId  = $uniqueId . '_input';

    // Adiciona o CSS
    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/chatInputComponent.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    // Lógica JavaScript injetada para lidar com o evento 'submit' do formulário
    $script[] = sprintf("
        document.addEventListener('DOMContentLoaded', function() {
            const formElement = document.getElementById('%s');
            const inputField  = document.getElementById('%s');

            formElement.addEventListener('submit', function(e) {
                // Impede que o formulário recarregue a página
                e.preventDefault();

                const textValue = inputField.value.trim();
                if (!textValue) return; // Ignora se o texto estiver vazio

                const action     = '%s';
                const actionType = '%s';

                // Dispara uma função JS na página
                if (actionType === 'function') {
                    if (typeof window[action] === 'function') {
                        window[action](textValue);
                    } else {
                        console.error('Waranas Lib: Função JS ' + action + ' não encontrada.');
                    }
                } 
                // Dispara um POST para um Endpoint
                else if (actionType === 'endpoint') {
                    fetch(action, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ mensagem: textValue })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const event = new CustomEvent('chatEndpointSuccess', { detail: data });
                        document.dispatchEvent(event);
                    })
                    .catch(error => console.error('Waranas Lib Erro de Fetch:', error));
                }

                // Limpa o campo de input após o envio
                inputField.value = '';
            });
        });
    ", $formId, $inputId, $safeAction, $safeActionType);

    // HTML do Componente agora com as tags <form> e <button type="submit">
    $inputArea = sprintf('
        <form id="%s" class="chat-input-area" action="#" method="POST">
            <input type="text" id="%s" class="chat-input-field" placeholder="%s" autocomplete="off" required>
            <button type="submit" class="chat-input-btn">%s</button>
        </form>
    ', $formId, $inputId, $safePlaceholder, $safeButtonText);

    return $inputArea;
}

?>