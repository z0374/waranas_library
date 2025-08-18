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
$lang = 'pt-br';

// Arrays para estilos e scripts dinâmicos (injetados na página)
$style = [];
$styleVar = [];
$mobile = [];
$script = [];

// Arrays para rastrear os arquivos externos necessários
$css_files = [];
$script_files = [];

// Adiciona o CSS de reset globalmente
    $component_css = ROOT_PATH . '/public/assets/css/reset.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }