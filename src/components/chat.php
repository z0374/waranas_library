<?php

/**
 * Renderiza o widget de chat completo, integrando o quadro, os balões e a barra de input.
 *
 * @param string $title           O título que aparece no cabeçalho do chat.
 * @param string $action          A ação a executar ('function' JS ou 'endpoint' URL).
 * @param string $actionType      O tipo de ação ('function' ou 'endpoint').
 * @param array  $initialMessages Array associativo com as mensagens iniciais [['text' => '...', 'sender' => 'system']].
 * @param string $size            O tamanho (largura) do widget inteiro (ex: '400px', '100%').
 * @param string $ratio           A proporção do quadro de mensagens (ex: '9/16' para formato telemóvel).
 * @return string                 O HTML completo do widget de chat.
 */
function chatComponent(
    $title = 'Assistente Virtual', 
    $action = 'processarNovaMensagem', 
    $actionType = 'function', 
    $initialMessages = [], 
    $size = '400px', 
    $ratio = '9/16'
) {
    global $css_files;

    // Tratamento de segurança para o título
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeSize  = htmlspecialchars($size, ENT_QUOTES, 'UTF-8');

    // 1. Processar as mensagens iniciais usando o chatBubbleComponent (ou Bubble)
    $boardContent = '';
    if (!empty($initialMessages) && is_array($initialMessages)) {
        foreach ($initialMessages as $msg) {
            $text   = isset($msg['text']) ? $msg['text'] : '';
            $sender = isset($msg['sender']) ? $msg['sender'] : 'system';
            
            if (!empty($text)) {
                // Presumo que a função se chame chatBubbleComponent de acordo com a nossa criação anterior
                // Se a renomeou estritamente para Bubble(), altere a linha abaixo para Bubble($text, $sender)
                $boardContent .= chatBubbleComponent($text, $sender); 
            }
        }
    }

    // 2. Gerar o Quadro de Mensagens (O tamanho aqui é 100% porque o limite será dado pelo contentor pai)
    $boardHtml = boardComponent('100%', $ratio, $boardContent);

    // 3. Gerar a Barra de Input
    $inputHtml = chatInputComponent($action, $actionType, 'Escreva a sua mensagem...', 'Enviar');

    // 4. Adicionar o CSS específico do contentor global do Chat
    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/chatComponent.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    // 5. Estrutura HTML final (Wrapper)
    $chatWidget = sprintf('
        <div class="chat-widget-container" style="--chat-widget-size: %s;">
            <div class="chat-widget-header">%s</div>
            
            <div class="chat-widget-body">
                %s
            </div>
            
            <div class="chat-widget-footer">
                %s
            </div>
        </div>
    ', $safeSize, $safeTitle, $boardHtml, $inputHtml);

    return $chatWidget;
}

?>