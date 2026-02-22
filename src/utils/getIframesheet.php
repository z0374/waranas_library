<?php
/**
 * Regista uma URL e retorna um ID único para uso em componentes.
 */
function getIframesheetRef($url) {
    global $iframesheet;

    // Gera um ID consistente baseado na URL
    $id = "iframe_" . substr(md5($url), 0, 8);

    if (!isset($iframesheet[$url])) {
        $iframesheet[$url] = $id;
    }

    return $id;
}