<?php
function hamburguer($mode, $array, $tm) {
    global $style, $script_files;

    // Registra o script do componente para ser carregado
    $component_script = 'assets/js/components/hamburguer.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    $hamburguer = [];
    $length = count($array);

    // Os estilos são mantidos aqui por dependerem da variável $tm
    $style[] = ".hamburguer #dt" . ($length - 1) . "{border-radius:0 0 0.24vw 0.24vw;}
                .hamburguer #dt0{border-radius:0.24vw 0.24vw 0 0;}
                .hamburguer summary{width:calc({$tm}vw*9);background:rgba(90,90,90);padding:3% 0;border-radius:0.24vw;font-weight:bold;list-style:none;font-size:calc({$tm}vw /2.5);text-align:center;cursor:pointer;}
                .hamburguer details[open] summary{border-radius:0.24vw 0.24vw 0 0;}";

    if ($mode == 'accordeon') {
        $style[] = "
            .hamburguer details{width:calc({$tm}vw*9);position:relative;background:rgba(90,90,90);border-top:solid 0.06vw rgba(30,30,30,0.6);}
            .hamburguer details p{width:calc({$tm}vw*9);position:relative;background:rgba(150,150,150);padding:10px calc({$tm}vw*0.15);}
        ";
    }

    if ($mode == 'dropdown') {
        $style[] = "
            .hamburguer details{display:flex;flex-flow:row wrap;justify-content:space-around;border-top:solid 0.03vw #f3f3f3;border-radius:0.24vw;}
            .hamburguer details p{width:calc({$tm}vw*15);position:absolute;background:rgba(150,150,150);padding:10px calc({$tm}vw*0.15);}
        ";
    }

    for ($i = 0; $i < $length; $i++) {
        $hamburguer[] = "<details id='dt{$i}'><summary>" . $array[$i]['title'] . "</summary><p>" . $array[$i]['content'] . "</p></details>";
    }

    return "<div class='hamburguer'>" . implode('', $hamburguer) . "</div>";
}