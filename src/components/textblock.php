<?php
function textblock($texto, $bg, $tm) {
    global $style;

    // Estilos 100% dinâmicos baseados nos parâmetros
    $style[] = "
        .textblock{width:100%;height:auto;font-size:{$tm};overflow:visible;padding:1.8%;text-align:justify;display:flex;justify-content:center;border-radius:calc({$tm}*0.03vw);display:flex;background:{$bg};}
        .textblock p{width:84%;height:auto;overflow:visible;text-align:justify;font-size:90%;}
    ";
    
    return "<div class='textblock'><p>{$texto}</p></div>";
}