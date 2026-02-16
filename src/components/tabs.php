<?php
function tabs($array, $bg) {
    global $style, $media_mobileP, $media_desktopL;
    $tabs = [];
    $content = [];
    $check = [];
    $indicatorRules = []; // Regras para a camada que se move
    
    $length = count($array);

    if(empty($array) || $length == 0) { 
        $tabs[] = "<label for='tabXXX'></label>";
        $content[] = "<input class='tabsBt' name='tabs' type='radio' id='tabXXX' checked><div class='content' id='cntXXX'><p></p></div>";
        $check[] = "input#tabXXX:checked ~ #cntXXX";
    }
    else {
        for ($i = 0; $i < $length; $i++) {
            $tabs[] = "<label for='tab{$i}'>{$array[$i]['title']}</label>";
            $content[] = "<input class='tabsBt' name='tabs' type='radio' id='tab{$i}' " . ($i == 0 ? 'checked' : '') . "><div class='content' id='cnt{$i}'>{$array[$i]['content']}</div>";
            
            $check[] = "input#tab{$i}:checked ~ #cnt{$i}";

            // Regra: Se o input X estiver checado, mude a opacidade do label X e mova o indicador
            $pos = (100 / $length) * $i;
            $style[] = ".tabs:has(#tab{$i}:checked) .labelsTab label[for='tab{$i}'] { opacity: 1; }";
            $style[] = ".tabs:has(#tab{$i}:checked) .tab-indicator { left: {$pos}%; }";
        }
    }

    $style[] = "
        .tabs { width:100%; display:grid; grid-template-columns:1fr; position:relative; 
    overflow: visible;}
        .labelsTab { 
            width:100%; display:grid; grid-auto-flow:column; grid-auto-columns: 1fr; 
            position: relative; background: {$bg}; 
            border-radius:0.9rem 0.9rem 0 0;
        }
        .tabs .labelsTab label { 
            padding:6%; font-size:1.2rem; font-weight:bold; 
            text-align:center; cursor:pointer;
            transition: opacity 0.3s; z-index: 2; 
        }
        
        /* A segunda camada (Indicador sobreposto) */
        .tab-indicator {
            position: absolute;
            top: 0; bottom: 0;
            width: " . (100 / max(1, $length)) . "%;
            background-color: #69696942; /* Cor da camada sobreposta */
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
            pointer-events: none;
        }

        .tabs .tabsBt { display:none; }
        .tabs .content { display:none; padding:2.1vh; border:solid 1px {$bg}; text-align:justify; }
        " . implode(', ', $check) . "{ display:block; }
    ";
    
    $media_mobileP[] = ".tabs .content{font-size:1rem;}";

    $media_desktopL[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/desktopL/tabs.css");

    return "
    <div class='tabs'>
        <div class='labelsTab'>
            " . implode('', $tabs) . "
            <div class='tab-indicator'></div>
        </div>
        " . implode('', $content) . "
    </div>";
}