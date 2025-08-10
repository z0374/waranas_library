<?php
function menu($array, $bg) {
    global $style, $css_files;

    $component_css = 'assets/css/components/menu.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    // Apenas o estilo dinâmico permanece aqui
    $style[] = ".navegation{ background-color:{$bg}; }";

    $nav = [];
    $length = count($array);
    for ($i = 0; $i < $length; $i++) {
        $nav[] = "<a id='menu{$i}' href='{$array[$i]['url']}'>{$array[$i]['content']}</a>";
    }
    return "<nav class='navegation'>" . implode('', $nav) . "</nav>";
}