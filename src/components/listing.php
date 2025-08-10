<?php
function listing($array) {
    global $style, $css_files;

    $component_css = 'assets/css/components/listing.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }
    // A linha $style[] foi removida.

    $ul = [];
    $length = count($array);
    for ($v = 0; $v < $length; $v++) {
        $ul[] = "<li><{$array[$v]['tag']} id='a{$v}' href={$array[$v]['href']}>{$array[$v]['content']}</{$array[$v]['tag']}></li>";
    }
    return "<ul id='listing'>" . implode('', $ul) . "</ul>";
}