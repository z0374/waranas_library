<?php

/**
 * Adiciona imagens à fila de carregamento tardio
 */
function waranas_asset_enqueue($url, array $selectors) {
    // Usa uma variável global para manter a fila durante a execução do script
    if (!isset($GLOBALS['WARANAS_ASSET_QUEUE'])) {
        $GLOBALS['WARANAS_ASSET_QUEUE'] = [];
    }
    
    $GLOBALS['WARANAS_ASSET_QUEUE'][] = [
        'url' => $url,
        'targets' => $selectors
    ];
}

/**
 * Renderiza o payload JSON para o JavaScript
 */
function waranas_render_assets() {
    if (empty($GLOBALS['WARANAS_ASSET_QUEUE'])) {
        return '';
    }
    
    $json = json_encode($GLOBALS['WARANAS_ASSET_QUEUE']);
    return "<script>window.WaranasAssets = {$json};</script>";
}