<?php
/**
 * Cria o placeholder e registra a URL no sistema de memória.
 */
function setIframesheet($id, $url, $type = 'iframe') {
    global $script, $script_files;

    // Garante o carregamento do JS
    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/iframesheet.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    // Obtém a referência única (eblob_...)
    $ref = getIframesheetRef($url);

    // Registra o comando JS para processar esta instância
    $script[] = "initIframesheet('{$id}', '{$ref}', '{$url}', '{$type}');";

    return "<div id='{$id}' class='iframesheet-placeholder'></div>";
}