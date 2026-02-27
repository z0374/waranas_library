<?php
/**
 * Regista o placeholder e prepara o transporte para a memória RAM com suporte a Cache Incremental.
 * @param string $id    ID único do elemento.
 * @param string $url   URL do recurso.
 * @param string $type  Tipo ('iframe' ou 'embed').
 * @param string $class Classe CSS opcional para o elemento final.
 */
function setIframesheet($id, $url, $type = 'iframe', $class = '') {
    global $script, $script_files, $css_files, $SITE_ENTRY_POINT;
    
    // --- ESTADOS ESTÁTICOS ---
    static $global_start_time = null;
    static $current_depth = 0; // 0=Página A, 1=Sub-página B, 2=Sub-página C
    static $internal_cache = [];
    
    // Orçamentos de Tempo (Segundos)
    $budget_B_total = 1.6; // Orçamento total para processamento no servidor (Nível B)
    $timeout_C_cada = 0.5; // Tempo máximo permitido para cada requisição profunda (Nível C)
    // -------------------------

    if ($global_start_time === null) $global_start_time = microtime(true);

    $ref = getIframesheetRef($url); // Gera ID consistente baseado na URL
    $internalContent = 'null';

    // Registro de Assets Críticos
    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/iframesheet.js';
    if (!in_array($component_script, $script_files)) $script_files[] = $component_script;
    
    if (!in_array(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/iframeSheet.css", $css_files)) {
        $css_files[] = ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/iframeSheet.css";
    }

    // Normalização da URL para o JavaScript
    $isRelative = !preg_match("~^(?:f|ht)tps?://~i", $url);
    $absoluteUrlForJs = $isRelative ? (defined('LOCAL_URI') ? LOCAL_URI : '') . '/' . ltrim($url, '/') : $url;

    // Lógica de Processamento de Sub-requisições (Server-Side)
    if ($isRelative && $current_depth < 3) {
        $now = microtime(true);
        $total_elapsed = $now - $global_start_time;
        
        $pode_processar = false;

        // Verificação de orçamento por nível de profundidade
        if ($current_depth === 0) { 
            if ($total_elapsed < $budget_B_total) $pode_processar = true;
        } 
        elseif ($current_depth === 1) { 
            $pode_processar = true; 
        }

        if ($pode_processar) {
            if (isset($internal_cache[$url])) {
                $internalContent = $internal_cache[$url];
            } else {
                $current_depth++;
                
                $originalUri = $_SERVER['REQUEST_URI'];
                $originalGet = $_GET;

                // Prepara a URI para o roteador interno
                $_SERVER['REQUEST_URI'] = strtok($url, '?');
                parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $new_get);
                $_GET = array_merge($_GET, $new_get);
                
                $entryPoint = (isset($SITE_ENTRY_POINT) && $SITE_ENTRY_POINT != "") ? $SITE_ENTRY_POINT : ROOT_PATH_WARANAS_LIB . '/index.php';

                if (file_exists($entryPoint)) {
                    ob_start();
                    $GLOBALS['WARANAS_INTERNAL_REQ'] = true; // Evita recursão no index.php
                    $GLOBALS['WARANAS_LEVEL'] = $current_depth;
                    
                    if ($current_depth === 2) @set_time_limit(2); // Limite de segurança nível C

                    include $entryPoint;
                    
                    $html = ob_get_clean();
                    $internal_cache[$url] = json_encode($html); // Salva no cache da execução
                    $internalContent = $internal_cache[$url];

                    // Lógica de Cache Incremental: sinaliza se o cache físico precisa ser atualizado
                    if (isset($GLOBALS['EXISTING_CACHE_DATA']) && 
                        (!isset($GLOBALS['EXISTING_CACHE_DATA'][$id]) || $GLOBALS['EXISTING_CACHE_DATA'][$id] === 'null')) {
                        $GLOBALS['CACHE_NEEDS_UPDATE'] = true;
                    }
                }
                
                $_SERVER['REQUEST_URI'] = $originalUri;
                $_GET = $originalGet;
                $current_depth--;
            }
        }
    }

    // Injeta a chamada do Singleton no JS. Se internalContent não for null, o carregamento é instantâneo
    $script[] = "initIframesheet('{$id}', '{$ref}', '{$absoluteUrlForJs}', '{$type}', '{$class}', {$internalContent});";
    return "<div id='{$id}' class='iframesheet-placeholder {$class}'></div>";
}