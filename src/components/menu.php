<?php

function menu($array, $bg) {
    $nav = [];
    global $style, $css_files;

    $style[] = "
        .navegation{
            background-color:{$bg};
        }
        "; // style do menu
    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/menu.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }
    $length = count($array);

    foreach ($array as $item) {
        if (array_key_exists('content', $item) && array_key_exists('url', $item)) {
            $nav[] = "<a class='menuButton' href='{$item['url']}'>{$item['content']}</a>";
        } else if (array_key_exists('content', $item) && array_key_exists('for', $item)) {
            $nav[] = "<label class='menuLabel' id='menuLabel{$item['content']}' for='menu{$item['for']}'>{$item['content']}</label>";
            $style[] = "#menu{$item['for']}:checked ~ #menuContent{$item['for']}{visibility:visible; display:flex;}";
        }
    }
    return "<nav class='navegation'>" . implode('', $nav) . "</nav>";
}