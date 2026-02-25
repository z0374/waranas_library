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
    
    // --- CONTROLO DE ESTADO ---
    static $global_start_time = null;
    static $current_depth = 0; // 0=A, 1=B, 2=C
    static $internal_cache = [];
    
    // CONFIGURAÇÕES DE TEMPO
    $budget_B_compartilhado = 1.6; // Orçamento total para todos os iframes nível B
    $timeout_C_estatico = 0.4;    // Tempo fixo máximo para cada página nível C
    // --------------------------

    if ($global_start_time === null) $global_start_time = microtime(true);

    $ref = getIframesheetRef($url);
    $internalContent = 'null';

    // Registro de assets
    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/iframesheet.js';
    if (!in_array($component_script, $script_files)) $script_files[] = $component_script;
    $css_files[] = ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/iframeSheet.css";

    $isRelative = !preg_match("~^(?:f|ht)tps?://~i", $url) && (strpos($url, '/') === 0 || strpos($url, '.') === false);

    if ($isRelative && $current_depth < 3) {
        $now = microtime(true);
        $total_elapsed = $now - $global_start_time;
        
        $pode_processar = false;

        if ($current_depth === 0) { // Estamos gerando o nível B dentro de A
            if ($total_elapsed < $budget_B_compartilhado) $pode_processar = true;
        } elseif ($current_depth === 1) { // Estamos gerando o nível C dentro de B
            // Nível C tem seu próprio fôlego estático (independente do orçamento de B)
            $pode_processar = true; 
            // O controle de tempo do C é feito dentro do include via set_time_limit ou checagem manual
        }

        if ($pode_processar) {
            if (isset($internal_cache[$url])) {
                $internalContent = $internal_cache[$url];
            } else {
                $current_depth++;
                $originalUri = $_SERVER['REQUEST_URI'];
                $_SERVER['REQUEST_URI'] = $url;
                
                $entryPoint = defined('SITE_ENTRY_POINT') ? SITE_ENTRY_POINT : $_SERVER['DOCUMENT_ROOT'] . '/index.php';
                
                if (file_exists($entryPoint)) {
                    ob_start();
                    $GLOBALS['WARANAS_LEVEL'] = $current_depth;
                    
                    // No nível C, forçamos um limite de tempo estático se possível
                    if ($current_depth === 2) @set_time_limit(2); 

                    include $entryPoint;
                    
                    $html = ob_get_clean();
                    $internal_cache[$url] = json_encode($html);
                    $internalContent = $internal_cache[$url];
                }
                
                $_SERVER['REQUEST_URI'] = $originalUri;
                $current_depth--;
            }
        }
    }

    $script[] = "initIframesheet('{$id}', '{$ref}', '{$url}', '{$type}', '{$class}', {$internalContent});";
    return "<div id='{$id}' class='iframesheet-placeholder {$class}'></div>";
}