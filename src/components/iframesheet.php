<?php
/**
 * Gera o Hub invisível com todas as fontes registradas.
 */
function hubIframesheet() {
    global $iframesheet;
    if (empty($iframesheet)) return '';

    $iframes = [];
    foreach ($iframesheet as $url_reg => $ref_id) {
        // Fontes invisíveis para criação dos Blobs na RAM
        $iframes[] = "<iframe id='{$ref_id}-source' src='{$url_reg}' style='display:none;'></iframe>";
    }
    
    return "
        <div id='iframesheet-hub' style='position:absolute; width:0; height:0; overflow:hidden; pointer-events:none;'>
            " . implode('', $iframes) . "
        </div>";
}