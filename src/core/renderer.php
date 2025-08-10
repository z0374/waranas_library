<?php
function html($tempo = 'real-time') {
    // Acessa todas as variáveis globais necessárias para construir a página.
    global $lang, $head, $fonts, $style, $mobile, $styleLink, $body, $header, $main, $footer, $script, $script_files, $css_files, $title, $favicon;

    // --- Monta a tag <head> ---
    $head_html = "<head>\n";
    $head_html .= "    <meta charset='utf-8'>\n";
    $head_html .= "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
    $head_html .= "    <title>" . htmlspecialchars(implode('', $title)) . "</title>\n";
    if (!empty($favicon)) {
        $head_html .= "    <link rel='icon' href='" . htmlspecialchars(implode('', $favicon)) . "'>\n";
    }

    // 1. Carrega o CSS global.
    $head_html .= "    <link rel='stylesheet' href='assets/css/style.css'>\n";

    // 2. Carrega os arquivos CSS dos componentes sob demanda.
    foreach (array_unique($css_files) as $file) {
        $head_html .= "    <link rel='stylesheet' href='" . htmlspecialchars($file) . "'>\n";
    }

    // 3. Carrega o styleLink customizado, se houver.
    if (!empty($styleLink[0])) {
         $head_html .= "    <link rel='stylesheet' href='" . htmlspecialchars($styleLink[0]) . "'>\n";
    }

    // 4. Injeta os estilos dinâmicos (gerados pelo PHP).
    if (!empty($fonts) || !empty($style) || !empty($mobile)) {
        $head_html .= "    <style>\n";
        $head_html .= implode("\n", $fonts);
        $head_html .= implode("\n", $style);
        if(!empty($mobile)){
            $head_html .= "    @media(max-width:920px){\n";
            $head_html .= implode("\n", $mobile);
            $head_html .= "    }\n";
        }
        $head_html .= "    </style>\n";
    }
    $head_html .= "</head>\n";

    // --- Monta a tag <body> ---
    $body_html = "<body>\n";
    $body_html .= "    <header>" . implode('', $header) . "</header>\n";
    $body_html .= "    <main>" . implode('', $main) . "</main>\n";
    $body_html .= "    <footer>" . implode('', $footer) . "</footer>\n";
    $body_html .= implode('', $body); // Para modais e outros elementos no fim do body

    // 1. Carrega o JS global.
    $body_html .= "    <script src='assets/js/main.js'></script>\n";

    // 2. Carrega os arquivos JS dos componentes sob demanda.
    foreach (array_unique($script_files) as $file) {
        $body_html .= "    <script src='" . htmlspecialchars($file) . "'></script>\n";
    }
    
    // 3. Injeta os scripts de inicialização dinâmicos.
    if (!empty($script)) {
        $body_html .= "    <script>\n        document.addEventListener('DOMContentLoaded', () => {\n";
        $body_html .= "            " . implode("\n            ", $script) . "\n";
        $body_html .= "        });\n    </script>\n";
    }
    $body_html .= "</body>\n";

    // --- Combina tudo ---
    $final_html = "<!DOCTYPE html>\n<html lang='" . htmlspecialchars($lang) . "'>\n" . $head_html . $body_html . "</html>";

    // Lógica de cache e exibição
    if ($tempo === 'cache') {
        $cacheTitle = !empty($title) ? normalize($title[0]) : 'cache-' . time();
        cachePage($cacheTitle, $final_html, 'create');
    } else {
        echo $final_html;
    }
}