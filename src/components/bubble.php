<?php

/**
 * Renderiza o componente de balão de mensagem do chat.
 *
 * @param string $text   O conteúdo da mensagem.
 * @param string $sender O remetente da mensagem (ex: 'user', 'system', 'error'). Define o estilo e alinhamento.
 * @return string        O HTML renderizado do balão.
 */
function bubble($text = '', $sender = 'system') {
    
    // Importa as arrays globais de dependências do sistema
    global $css_files;

    // Tratamento básico de segurança (XSS)
    $safeText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    
    // Limpa a string do sender para garantir que seja uma classe CSS válida e segura
    $safeSender = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', $sender));

    // Adiciona o CSS do componente, se ainda não estiver na lista
    $component_css = ROOT_PATH_WARANAS_LIB . '/assets/css/components/bubble.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    // HTML do balão de mensagem
    // A classe principal é 'chat-bubble' e a classe dinâmica ($safeSender) define cor/alinhamento
    $bubble = sprintf('
        <div class="chat-bubble %s">
            <span class="bubble-text">%s</span>
        </div>
    ', $safeSender, $safeText);

    return $bubble;
}

?>