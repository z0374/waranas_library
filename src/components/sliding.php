<?php
function sliding($direction, $array, $id, $bg) {
    global $style, $css_files;

    // Registra a folha de estilo estática
    $component_css = 'assets/css/components/sliding.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    $isld = '';
    $length = count($array);
    $stylesDir = [
        'top' => ".sld{width:100vw;height:15vh;top:-15vw;left:0;transform:translateY(0);border-radius:0 0 0 0;}.sld:target{transform: translateY(15vw);}",
        'bottom' => ".sld{width:100vw;height:15vh;top:0;left:0;transform:translateY(100vh);border-radius:1.5vw 1.5vw 0 0;}.sld:target{transform: translateY(85vh);}",
        'left' => '.sld{width:15vw;height:100vh;top:0;left:-15vw;transform:translateX(0);border-radius:0 1.5vw 0 0;}.sld:target{transform: translateX(15vw);}',
        'right' => '.sld{width:15vw;height:100vh;top:0;left:0;transform:translateX(100vw);border-radius:1.5vw 0 0 0;}.sld:target{transform: translateX(85vw);}'
    ];

    // Estilos dinâmicos (dependentes dos parâmetros)
    $style[] = "
        .sld{border:solid 0.03vw {$bg};border-top:solid 2.1vw {$bg};}
        {$stylesDir[$direction]}
        .sld #{$id}" . ($length - 1) . "{background-color:#EAC7EB;padding:0.6vw 4.2vw;border-radius:0.3vw;}
    ";

    for ($v = 0; $v < $length; $v++) {
        $isld .= "<a id='{$id}{$v}' href='{$array[$v]['href']}'>{$array[$v]['content']}</a>";
    }
    return "<div id='{$id}' class='sld'>" . $isld . "</div>";
}