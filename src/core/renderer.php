<?php

function html($tempo = 'real-time') {
    global $lang, $head, $fonts, $style, $styleVar, $styleLink, $body, $header, $main, $footer, $script, $mobile, $title, $favicon, $script_files, $css_files; // Adiciona $css_files

    $lang = !empty($lang) ? $lang : 'pt-br';
    $head[] = "<title>" . implode('', $title) . "</title>";
    $head[] = "<link rel='icon' href='" . implode('', $favicon) . "'>";
    $head[] = "<meta charset='utf-8'>";
    $head[] = "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    // --- Monta a string do <head> ---
    $head_html = "<head>" . implode('', $head);

    // 1. Carrega o CSS global
    for($i = 0; $i < count($css_files); $i++){
        $css_files[$i] = file_get_contents($css_files[$i]);
    }
    
    array_unshift($style, ...$css_files);

    // 2. Carrega os arquivos CSS dos componentes (sob demanda)
    /*$unique_css_files = array_unique($css_files);
    foreach ($unique_css_files as $file) {
        $head_html .= '<link rel="stylesheet" href="' . htmlspecialchars($file) . '">';
    }*/

    // 3. Carrega o styleLink customizado, se houver
    if (!empty($styleLink[0])) {
         $head_html .= "<link rel='stylesheet' href='" . htmlspecialchars($styleLink[0]) . "'>";
    }

    // 4. Injeta os estilos dinâmicos e de fontes na tag <style>
    $head_html .= "<style>
        :root {"
        . implode('', $styleVar)
        . "}"
        . implode('', $fonts)
        . implode('', $style) .
        "@media(max-width:900px){" . implode('', $mobile) . "}
    </style>";

    $head_html .= "</head>";
    // --- Fim da montagem do <head> ---


    // --- Monta o restante do documento ---
    $body_html = "<body>
        <header>" . implode('', $header) . "</header>
        <main>" . implode('', $main) . "</main>
        <footer>" . implode('', $footer) . "</footer>"
        . implode('', $body);

    // Adiciona os scripts JS (lógica inalterada da etapa anterior)
    
    for($i = 0; $i < count($script_files); $i++){
            $script_files[$i] = file_get_contents($script_files[$i]);
        }
    
    array_unshift($script, ...$script_files);

    if (!empty($script)) {
        $body_html .= '<script>' . implode('', $script) . '</script>';
    }
    $body_html .= "</body>";
    // --- Fim da montagem do body ---


    // --- Combina tudo ---
    $final_html = "<!DOCTYPE html><html lang=\"" . htmlspecialchars($lang) . "\">" . $head_html . $body_html . "</html>";


    // Lógica de cache e exibição
    switch ($tempo) {
        case "cache":
            $cacheTitle = isset($title[0]) ? normalize($title[0]) : 'cache-' . time();
            cachePage($cacheTitle, $final_html, 'create', false);
            break;
        case "real-time":
        default:
            echo $final_html;
            break;
    }
}