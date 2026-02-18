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
$media_mobile_portrait_geral = [];
$media_mobile_landscape_geral = [];
$media_tablet_portrait_geral = [];
$media_tablet_landscape_geral = [];
$media_desktop_portrait_geral = [];
$media_desktop_landscape_geral = [];
// --- 1. EXTRA SMALL (XS) < 576px ---
// Foco: Smartphones em modo retrato (uso com uma mão).
$media_xs_portrait = [];
$media_xs_landscape = [];
// --- 2. SMALL (SM) ≥ 576px ---
// Foco: Smartphones grandes (Max/Ultra) ou celulares deitados.
$media_sm_portrait = [];
$media_sm_landscape = [];

// --- 3. MEDIUM (MD) ≥ 768px ---
// Foco: Tablets (iPad/Galaxy Tab) em modo retrato. Início de layouts em colunas.
$media_md_portrait = [];
$media_md_landscape = [];

// --- 4. LARGE (LG) ≥ 992px ---
// Foco: Tablets deitados ou Laptops pequenos (Air/Netbooks).
$media_lg_portrait = [];
$media_lg_landscape = [];

// --- 5. EXTRA LARGE (XL) ≥ 1200px ---
// Foco: Desktops padrão e monitores Full HD.
$media_xl_portrait = [];
$media_xl_landscape = [];

// --- 6. DOUBLE EXTRA LARGE (2XL) ≥ 1400px ---
// Foco: Monitores Quad HD, 4K ou telas Ultrawide. 
$media_2xl_portrait = [];
$media_2xl_landscape = [];
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