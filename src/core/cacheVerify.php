<?php

/**
 * Nova função que verifica placeholders 'null' no cache e tenta processar
 */
function processPending($html) {
    // Regex para encontrar chamadas de initIframesheet com 'null'
    // Ex: initIframesheet('id', 'ref', 'url', 'type', 'class', null);
    $pattern = "/initIframesheet\('([^']+)',\s*'([^']+)',\s*'([^']+)',\s*'([^']*)',\s*'([^']*)',\s*null\);/";
    
    if (!preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
        return $html; // Nada para processar
    }

    $start_time = microtime(true);
    $budget = 1.6; // Orçamento de tempo em segundos
    $hasChanged = false;

    foreach ($matches as $match) {
        // Verifica se ainda temos tempo no orçamento
        if ((microtime(true) - $start_time) >= $budget) break;

        $fullMatch = $match[0];
        $id = $match[1];
        $ref = $match[2];
        $url = $match[3];
        $type = $match[4];
        $class = $match[5];

        // Tenta processar o conteúdo do Iframe (reutiliza a lógica interna)
        // Como estamos fora do renderer principal, simulamos o ambiente
        $internalHtml = fetchInternalContent($url);

        if ($internalHtml !== null) {
            $jsonContent = json_encode($internalHtml);
            $newCall = "initIframesheet('{$id}', '{$ref}', '{$url}', '{$type}', '{$class}', {$jsonContent});";
            $html = str_replace($fullMatch, $newCall, $html);
            $hasChanged = true;
        }
    }

    return $html;
}