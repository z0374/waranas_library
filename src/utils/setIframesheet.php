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
    static $current_depth = 0; // 0=A, 1=B, 2=C
    static $internal_cache = [];
    
    // Orçamentos de Tempo
    $budget_B_total = 1.6; // Segundos totais para todos os níveis B na página
    $timeout_C_cada = 0.5; // Tempo máximo permitido para cada requisição nível C
    // -------------------------

    if ($global_start_time === null) $global_start_time = microtime(true);

    $ref = getIframesheetRef($url);
    $internalContent = 'null';

    // Registro de Assets (Garante que o JS/CSS da lib sejam carregados)
    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/iframesheet.js';
    if (!in_array($component_script, $script_files)) $script_files[] = $component_script;
    if (!in_array(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/iframeSheet.css", $css_files)) {
        $css_files[] = ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/iframeSheet.css";
    }

    // Detecta se a URL é interna (começa com /)
    $isRelative = !preg_match("~^(?:f|ht)tps?://~i", $url);
    // LOCAL_URI deve estar definida em seu globals ou env
    $absoluteUrlForJs = $isRelative ? (defined('LOCAL_URI') ? LOCAL_URI : '') . '/' . ltrim($url, '/') : $url;

    if ($isRelative && $current_depth < 3) {
        $now = microtime(true);
        $total_elapsed = $now - $global_start_time;
        
        $pode_processar = false;

        // Regra de Nível B: Usa orçamento compartilhado
        if ($current_depth === 0) { 
            if ($total_elapsed < $budget_B_total) $pode_processar = true;
        } 
        // Regra de Nível C: Tem seu próprio tempo estático
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

                // Prepara a URI para o roteador (remove query strings da rota principal)
                $_SERVER['REQUEST_URI'] = strtok($url, '?');
                parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $new_get);
                $_GET = array_merge($_GET, $new_get);
                
                // --- ENTRY POINT ---
                $entryPoint = (isset($SITE_ENTRY_POINT) && $SITE_ENTRY_POINT != "") ? $SITE_ENTRY_POINT : ROOT_PATH_WARANAS_LIB . '/index.php';
                // -------------------

                if (file_exists($entryPoint)) {
                    ob_start();
                    $GLOBALS['WARANAS_INTERNAL_REQ'] = true;
                    $GLOBALS['WARANAS_LEVEL'] = $current_depth;
                    
                    // Se for nível C, limitamos o tempo de execução para evitar travamentos
                    if ($current_depth === 2) @set_time_limit(2); 

                    include $entryPoint;
                    
                    $html = ob_get_clean();
                    $internal_cache[$url] = json_encode($html);
                    $internalContent = $internal_cache[$url];

                    // --- LÓGICA DE CACHE INCREMENTAL ---
                    // Se processamos um conteúdo que no cache atual está como 'null', sinalizamos para atualizar o arquivo físico
                    if (isset($GLOBALS['EXISTING_CACHE_DATA']) && 
                        (!isset($GLOBALS['EXISTING_CACHE_DATA'][$id]) || $GLOBALS['EXISTING_CACHE_DATA'][$id] === 'null')) {
                        $GLOBALS['CACHE_NEEDS_UPDATE'] = true;
                    }
                    // -----------------------------------
                }
                
                $_SERVER['REQUEST_URI'] = $originalUri;
                $_GET = $originalGet;
                $current_depth--;
            }
        }
    }

    // Passa os dados para o JavaScript (initIframesheet lidará com o internalContent ser null ou HTML)
    $script[] = "initIframesheet('{$id}', '{$ref}', '{$absoluteUrlForJs}', '{$type}', '{$class}', {$internalContent});";
    return "<div id='{$id}' class='iframesheet-placeholder {$class}'></div>";
}