<?php

/**
 * Adiciona uma imagem e seus seletores à fila de carregamento.
 * * @param string $url Caminho da imagem (ex: /assets/images/foto.jpg)
 * @param array $selectors Lista de seletores CSS (ex: ['.minha-img', '#capa'])
 */
function waranas_asset_enqueue($url, array $selectors) {
    if (!isset($GLOBALS['WARANAS_ASSET_QUEUE'])) {
        $GLOBALS['WARANAS_ASSET_QUEUE'] = [];
    }
    
    $GLOBALS['WARANAS_ASSET_QUEUE'][] = [
        'url' => $url,
        'targets' => $selectors
    ];
}

/**
 * Retorna o bloco de script com os dados para o JavaScript.
 * Deve ser impresso no final do HTML.
 */
function waranas_render_assets() {
    global $script_files;
    if (empty($GLOBALS['WARANAS_ASSET_QUEUE'])) {
        return "";
    }

    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/preloader.js';
if (!in_array($component_script, $script_files)) {
    $script_files[] = $component_script;
}
    
    $json = json_encode($GLOBALS['WARANAS_ASSET_QUEUE']);
    return "<script id='waranas-preloader-data'>window.WaranasAssets = {$json};</script>";
}