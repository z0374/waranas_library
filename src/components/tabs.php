<?php
function tabs($array, $bg) {
    global $style, $mobile;
    $tabs = [];
    $content = [];
    $check = [];
    $length = count($array);

    for ($i = 0; $i < $length; $i++) {
        $tabs[] = "<label for='tab{$i}'>{$array[$i]['title']}</label>";
        $content[] = "<input class='tabsBt' name='tabs' type='radio' id='tab{$i}' " . ($i == 0 ? 'checked' : '') . "><div class='content' id='cnt{$i}'><p>{$array[$i]['content']}</p></div>";
        $check[] = "input#tab{$i}:checked ~ #cnt{$i}";
    }

    // Estilos 100% dinâmicos
    $style[] = "
        .tabs{width:100%;min-height:fit-content;display:grid;grid-template-columns:1fr;grid-template-rows:auto 1fr;border-radius:0.6vh 0.6vh 0 0;}
        .labelsTab{width:100%;grid-row:1;display:grid;grid-auto-flow:column;grid-auto-columns: 1fr;}
        .labelsTab label{min-height:100%;padding:0.9vw;font-size:1.5rem;font-weight:bold;background:{$bg};text-align:center;cursor:pointer;}
        .labelsTab label:hover{opacity:0.72;}
        .tabs .tabsBt{display:none;}
        .tabs .content{display:none; padding:2.1vh;grid-column:1/-1;grid-row:2;border:solid 1px {$bg};text-align:justify;overflow:visible;}
        " . implode(', ', $check) . "{display:block;}
    ";
    
    $mobile[] = ".tabs .content{font-size:1rem;}";

    return "<div class='tabs'><div class='labelsTab'>" . implode('', $tabs) . '</div>' . implode('', $content) . "</div>";
}