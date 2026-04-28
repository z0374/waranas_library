<?php

/**
 * Renderiza o widget de chat completo, integrando o quadro, os balões e a barra de input.
 * * @param array  $config Configurações de estilo e mensagens iniciais.
 * @param string $action URL do entrypoint para onde as mensagens criptografadas serão enviadas.
 */
function chatComponent($config = [], $action = '') {
    global $css_files, $script, $styleVar, $script_files;

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
    $safeTitle  = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeSize   = htmlspecialchars($size, ENT_QUOTES, 'UTF-8');
    $safeRatio  = htmlspecialchars($ratio, ENT_QUOTES, 'UTF-8');
    $safeAction = htmlspecialchars($action, ENT_QUOTES, 'UTF-8');

    // 1. Processar as mensagens iniciais
    // Note: Mantemos a estrutura de grid onde o bubble se posiciona via CSS (user/system)
    $boardContent = '';
    if (!empty($initialMessages) && is_array($initialMessages)) {
        foreach ($initialMessages as $msg) {
            $text   = $msg['text'] ?? '';
            $sender = $msg['sender'] ?? 'system';
            
            if (!empty($text)) {
                $boardContent .= bubble($text, $sender); 
            }
        }
    }

    // 2. Gerar o Quadro de Mensagens (Board)
    $boardHtml = boardComponent('100%', $safeRatio, $boardContent);

    // 3. Gerar a Barra de Input
    // O id 'sendMSG' será usado pelo script JS para capturar o envio
    $inputHtml = inputSubmit('sendMSG', 'endpoint', 'Escreva a sua mensagem...', 'Enviar');

    // 4. Adicionar Assets da Lib
    $component_css = ROOT_PATH_WARANAS_LIB . '/assets/css/components/chat.css';
    if (isset($css_files) && !in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    $styleVar[] = sprintf( '
        --chat-widget-size: %s;
        --chat-widget-ratio: %s;
        ', $safeSize, $safeRatio );

    // O arquivo chat.js agora deve conter a lógica de criptografia e fetch
    $component_script = ROOT_PATH_WARANAS_LIB . '/assets/js/components/chat.js';
    if (isset($script_files) && !in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }
    $buble = bubble("{{message}}", "placeholderBuble");
    // 5. Estrutura HTML final
    // data-endpoint: armazena a URL para o script de criptografia JS utilizar
    return sprintf('
        <div class="chat-widget-container" 
             style="max-width: var(--chat-widget-size); width: 100%%;"
             data-endpoint="%s">
            <div class="chat-widget-header">
                <strong>%s</strong>
            </div>
            <div class="chat-widget-body">
                %s
            </div>
            <div class="chat-widget-footer">
                %s
            </div>
            <div style="display:none;">
                %s
            </div>
        </div>
    ', $safeAction, $safeTitle, $boardHtml, $inputHtml, $buble);
}