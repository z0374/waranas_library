<?php
// Armazena partes da página que serão montadas no final.
$bgbody = [];
$title = [];
$favicon = [];
$header = [];
$main = [];
$footer = [];
$body = [];
$fonts = [];
$styleLink = [''];

$svg = null;
$lang = 'pt-br';

// Arrays para estilos e scripts dinâmicos (injetados na página)
$style = [];
$styleVar = [];
$mobile = [];
$media_mobileP = [];
$media_mobileL = [];
$media_desktopP = [];
$media_desktopL = [];
$script = [];

// Arrays para rastrear os arquivos externos necessários
$css_files = [];
$script_files = [];

// Adiciona o CSS de reset globalmente
    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/reset.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/main.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }