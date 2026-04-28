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
    $component_css = ROOT_PATH_WARANAS_LIB . '/assets/css/components/chatInputComponent.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    // Lógica JavaScript injetada para lidar com o evento 'submit' do formulário
    $script[] = sprintf("
    document.addEventListener('DOMContentLoaded', function() {
    const formElement = document.getElementById('%s');
    const inputField  = document.getElementById('%s');

    if (!formElement || !inputField) return;

    formElement.addEventListener('submit', function(e) {
        e.preventDefault();

        const textValue = inputField.value.trim();
        if (!textValue) return;

        const action = '%s'; // Nome da função JS (ex: sendMSG) ou URL

        /**
         * Ação Primária: 
         * Redireciona o fluxo para a função definida no parâmetro $action.
         * Se action for 'sendMSG', chamará window['sendMSG'](textValue).
         */
        if (typeof window[action] === 'function') {
            window[action](textValue);
        } else {
            console.warn('Waranas Lib: Action \"' + action + '\" não encontrada como função global.');
        }

        // Limpa apenas o campo de input
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