<?php
function listing($array) { //array[tag, property, content]
    global $style, $css_files;

    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/listing.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }
    // A linha $style[] foi removida.

    $ul = [];
    $length = count($array);
    for ($v = 0; $v < $length; $v++) {
        $ul[] = "<li><{$array[$v]['tag']} id='a{$v}' {$array[$v]['property']}}>{$array[$v]['content']}</{$array[$v]['tag']}></li>";
    }
    return "<ul id='listing'>" . implode('', $ul) . "</ul>";
}