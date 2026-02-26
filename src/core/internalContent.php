<?php

/**
 * Função auxiliar para processamento interno isolado
 */
function fetchInternalContent($url) {
    $isRelative = !preg_match("~^(?:f|ht)tps?://~i", $url) && (strpos($url, '/') === 0 || strpos($url, '.') === false);
    
    if (!$isRelative) return null;

    $entryPoint = defined('SITE_ENTRY_POINT') ? SITE_ENTRY_POINT : ROOT_PATH_WARANAS_LIB . '/index.php';
    if (!file_exists($entryPoint)) return null;

    $originalUri = $_SERVER['REQUEST_URI'];
    $_SERVER['REQUEST_URI'] = $url;
    
    ob_start();
    // Definimos um nível para evitar recursão infinita no processamento do cache
    $GLOBALS['WARANAS_LEVEL'] = 2; 
    
    try {
        include $entryPoint;
        $content = ob_get_clean();
    } catch (Exception $e) {
        ob_end_clean();
        $content = null;
    }

    $_SERVER['REQUEST_URI'] = $originalUri;
    return $content;
}