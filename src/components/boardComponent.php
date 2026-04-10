<?php

/**
 * Renderiza o componente do quadro.
 *
 * @param string $size     O tamanho base do componente (geralmente aplicado à largura, ex: '100%', '300px').
 * @param string $ratio    A proporção do componente (ex: '1/1' para quadrado, '16/9' para retangular).
 * @param string $content  O conteúdo padrão (HTML ou texto) a ser carregado via JS.
 * @return string          O HTML renderizado do componente.
 */
function boardComponent($size = '100%', $ratio = '1/2', $content = '') {
    
    // Importa as arrays globais do seu sistema 
    global $styleVar, $css_files, $script_files, $scrypt;

    // Tratamento básico para evitar quebra de HTML (XSS)
    $safeSize  = htmlspecialchars($size, ENT_QUOTES, 'UTF-8');
    $safeRatio = htmlspecialchars($ratio, ENT_QUOTES, 'UTF-8');

    // Adiciona o CSS
    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/boardComponent.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    // Adiciona o JS
    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/boardComponent.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    // Passa o conteúdo para JSON para evitar erros de sintaxe no JavaScript
    $safeContentJs = json_encode($content);
    
    // Adiciona o script de inicialização na array global de scripts inline
    $scrypt[] = sprintf('
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof atualizarQuadro === "function") {
                atualizarQuadro(%s);
            }
        });
    ', $safeContentJs);

    // Formata a string de estilos CSS inline de forma limpa e segura
    $styleVar[] = sprintf('
        --chat-size: %s;
        --chat-ratio: %s;', 
            $safeSize, $safeRatio);

    // HTML do quadro com as variáveis injetadas no atributo style
    $board = '
        <div 
            id="boardComponent"
            class="board-grid"
        >
        </div>
    ';

    return $board;
}

?>