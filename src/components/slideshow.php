<?php
function slideshow($id, $array, $tm, $auto) {
    global $style, $script, $fscript, $mobile, $css_files, $script_files;

    $component_css = 'assets/css/components/slideshow.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    $component_script = 'assets/js/components/slideshow.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    $items = [];
    $length = count($array);

    // Estilos dinâmicos
    $style[] = "
        .slideshow, #d{$id}{width:calc({$tm}vw*2);height:calc({$tm}vw*1);position:relative;border-radius:calc({$tm}*0.06vw);}
        .slideshow #bt{width:calc({$tm}vw*2 / 6); font-size:calc({$tm}vw*1/3); line-height:calc({$tm}vw*1);}
    ";
    $mobile[] = "
        #d{$id}{width:calc({$tm}vw*6);height:calc({$tm}vw*3);}
    ";

    for ($i = 0; $i < $length; $i++) {
        $items[] = "<a href='{$array[$i]['url']}' id='{$id}{$i}' class='item' target='blank' title='isto é um componente slideshow.'></a>";
        $style[] = "#{$id}{$i}{background:url({$array[$i]['image']}) no-repeat;background-size:contain;background-position:center;}";
    }

    // Script de inicialização dinâmico
    $script[] = "
    function slideshow{$id}(){
        let item = document.querySelector('#d{$id}').offsetWidth;
        let last = {$length};
        let id = '{$id}';
        slideshow(item, last, id);
    }";
    if ($auto !== 0) {
        $script[] = "setInterval(slideshow{$id}, {$auto} * 1000);";
    }

    return "<div class='slideshow' id='d{$id}'><div id='{$id}' class='items_slideshow'>" . implode('', $items) . "</div><button id='bt' onclick='slideshow{$id}()'> > </button></div>";
}