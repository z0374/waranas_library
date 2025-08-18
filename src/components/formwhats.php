<?php
function formwhats($id, $phone, $bg) {
    global $script, $style, $script_files;

    // Registra o script do componente
    $component_script = ROOT_PATH . '/public/assets/js/components/whatsapp.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    $idEsc = htmlspecialchars($id, ENT_QUOTES);
    $phoneEsc = htmlspecialchars($phone, ENT_QUOTES);
    $bgEsc = htmlspecialchars($bg, ENT_QUOTES);

    // Estilos dinâmicos específicos para o ID e cor do formulário
    $style[] = "
        form#{$idEsc} {width: 81%;display: flex;flex-flow: column nowrap;gap: 0.9rem;}
        form#{$idEsc} input,
        form#{$idEsc} textarea {width: 90%;height: 3rem;padding: 0.9rem;font-size: 1.14rem;border-radius: 0.3rem;box-shadow: 0 0 0.1rem 0.1rem rgba(78,78,78,0.09);}
        form#{$idEsc} textarea {height: 9rem;resize: none;}
        form#{$idEsc} input[type=submit] {background: {$bgEsc};color: #FFFFFF;font-weight: bold;}
    ";

    return "<form id=\"{$idEsc}\" onsubmit=\"sendwhats('{$idEsc}Nm','{$idEsc}Mm','{$phoneEsc}'); return false;\">
        <input id=\"{$idEsc}Nm\" type=\"text\" placeholder=\"Seu nome\">
        <textarea id=\"{$idEsc}Mm\" placeholder=\"Sua mensagem\"></textarea>
        <input id=\"{$idEsc}St\" value=\"ENVIAR\" type=\"submit\">
    </form>";
}