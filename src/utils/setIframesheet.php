<?php
/**
 * Regista o placeholder e prepara o transporte para a memória RAM.
 * @param string $id    ID único do elemento.
 * @param string $url   URL do recurso.
 * @param string $type  Tipo ('iframe' ou 'embed').
 * @param string $class Classe CSS opcional para o elemento final.
 */
function setIframesheet($id, $url, $type = 'iframe', $class = '') {
    global $script, $script_files, $css_files;

    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/iframesheet.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    $ref = getIframesheetRef($url);
    
    $css_files[] = ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/iframeSheet.css";
    // Passamos a classe como o quarto argumento para a função JS
    $script[] = "initIframesheet('{$id}', '{$ref}', '{$url}', '{$type}', '{$class}');";

    return "<div id='{$id}' class='iframesheet-placeholder {$class}'></div>";
}