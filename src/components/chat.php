<?php

/**
 * Renderiza o widget de chat completo, integrando o quadro, os balões e a barra de input.
 */
function chatComponent($config = []) {
    global $css_files, $styleVar, $script_files;

    // 1. Define os padrões e mescla com o que foi recebido
    $defaults = [
        'title' => 'Assistente Virtual',
        'initialMessages' => [],
        'size' => '400px',
        'ratio' => '9/16'
    ];

    $config = array_merge($defaults, $config);

    $title           = $config['title'];
    $initialMessages = $config['initialMessages'];
    $size            = $config['size'];
    $ratio           = $config['ratio'];

    // Tratamento de segurança
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeSize  = htmlspecialchars($size, ENT_QUOTES, 'UTF-8');
    $safeRatio = htmlspecialchars($ratio, ENT_QUOTES, 'UTF-8');

    // 1. Processar as mensagens iniciais com lógica de posicionamento
    $boardContent = '';
    if (!empty($initialMessages) && is_array($initialMessages)) {
        foreach ($initialMessages as $msg) {
            $text   = $msg['text'] ?? '';
            $sender = $msg['sender'] ?? 'system';
            
            if (!empty($text)) {
                // Define o alinhamento: 'user' à direita, outros à esquerda
                $alignmentClass = ($sender === 'user') ? 'chat-align-right' : 'chat-align-left';
                
                // O quadro envolve a bolha para determinar o lado
                $boardContent .= sprintf(
                    '<div class="chat-message-row %s">%s</div>',
                    $alignmentClass,
                    bubble($text, $sender)
                );
            }
        }
    }

    // 2. Gerar o Quadro de Mensagens
    $boardHtml = boardComponent('100%', $safeRatio, $boardContent);

    // 3. Gerar a Barra de Input
    $inputHtml = inputSubmit('sendMSG', 'function', 'Escreva a sua mensagem...', 'Enviar');

    // 4. Adicionar Assets
    $component_css = ROOT_PATH_WARANAS_LIB . '/assets/css/components/chat.css';
    if (isset($css_files) && !in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    $styleVar[] = sprintf( '
        --chat-widget-size: %s;
        --chat-widget-ratio: %s;
        ', $safeSize, $safeRatio );

    $component_script = ROOT_PATH_WARANAS_LIB . '/assets/js/components/chat.js';
    if (isset($script_files) && !in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    // 5. Estrutura HTML final
    return sprintf('
        <div class="chat-widget-container" style="max-width: var(--chat-widget-size); width: 100%%;">
            <div class="chat-widget-header">%s</div>
            <div class="chat-widget-body">%s</div>
            <div class="chat-widget-footer">%s</div>
        </div>
    ', $safeTitle, $boardHtml, $inputHtml);
}