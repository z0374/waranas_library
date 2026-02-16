<?php
function html($tempo = 'real-time') {
    global $lang, $head, $fonts, $style, $styleVar, $styleLink, $media_mobileP, $media_mobileL, $media_desktopP, $media_desktopL, $SVG, $body, $header, $main, $footer, $script, $title, $favicon, $script_files, $css_files;

    $lang = !empty($lang) ? $lang : 'pt-br';
    $cacheTitle = isset($title[0]) ? $title[0] : 'index';

    // 1. TENTATIVA DE CACHE PRECOCE (Antes de processar loops pesados)
    if ($tempo === 'cache') {
        // Se o arquivo existir e for válido, cachePage dará exit() aqui.
        cachePage($cacheTitle, null, 'return');
    }

    // 2. PROCESSAMENTO DO FAVICON
    $favUrl = is_array($favicon) ? implode('', $favicon) : $favicon;
    $path = parse_url($favUrl, PHP_URL_PATH);
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    $mimeType = match ($ext) {
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'jpg', 'jpeg' => 'image/jpeg',
        default => 'image/svg+xml'
    };

    // 3. MONTAGEM DO <head>
    $head[] = "<meta charset='utf-8'>";
    $head[] = "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    $head[] = "<title>" . implode('', $title) . "</title>";
    $head[] = "<link rel='icon' type='{$mimeType}' href='{$favUrl}'>";

    // 4. PROCESSAMENTO DE CSS (Injeção Crítica)
    // Lemos os arquivos físicos apenas se o cache não foi servido acima
    $css_inline = "";
    if (!empty($css_files)) {
        foreach ($css_files as $file) {
            if (file_exists($file)) {
                $css_inline .= file_get_contents($file);
            }
        }
    }

    $head_html = "<head>" . implode('', $head);

    // Estilos Externos
    if (!empty($styleLink[0])) {
         $head_html .= "<link rel='stylesheet' href='" . htmlspecialchars($styleLink[0]) . "'>";
    }

    // Estilos Internos Otimizados
    $head_html .= "<style>
        :root {" . implode('', $styleVar) . "}
        " . implode('', $fonts) . "
        " . $css_inline . "
        " . implode('', $style) . "
        @media screen and (max-width: 920px) and (orientation: portrait) {" . implode('', $media_mobileP) . "}
        @media screen and (max-width: 920px) and (orientation: landscape) {" . implode('', $media_mobileL) . "}
        @media screen and (min-width: 920px) and (orientation: portrait) {" . implode('', $media_desktopP) . "}
        @media screen and (min-width: 920px) and (orientation: landscape) {" . implode('', $media_desktopL) . "}
    </style></head>";

    // 5. MONTAGEM DO <body>
    $body_html = "<body>
        " . ($SVG ?? '') . "
        <header>" . implode('', $header) . "</header>
        <main>" . implode('', $main) . "</main>
        <footer>" . implode('', $footer) . "</footer>"
        . implode('', $body);

    // 6. PROCESSAMENTO DE SCRIPTS
    $js_inline = "";
    if (!empty($script_files)) {
        foreach ($script_files as $s_file) {
            if (file_exists($s_file)) {
                $js_inline .= file_get_contents($s_file);
            }
        }
    }

    if (!empty($js_inline) || !empty($script)) {
        $body_html .= '<script>' . $js_inline . implode('', $script) . '</script>';
    }
    $body_html .= "</body>";

    // 7. RESULTADO FINAL
    $final_html = "<!DOCTYPE html>\n<html lang=\"" . htmlspecialchars($lang) . "\">\n" . $head_html . $body_html . "\n</html>";

    // 8. ENTREGA E ARMAZENAMENTO
    switch ($tempo) {
        case "cache":
            // Cria o cache e exibe (com exit interno)
            cachePage($cacheTitle, $final_html, 'create');
            break;
        case "real-time":
        default:
            header("Content-Type: text/html; charset=UTF-8");
            header("X-Cache: BYPASS");
            echo $final_html;
            break;
    }
}