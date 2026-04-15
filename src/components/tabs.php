<?php
function tabs($array, $bg) {
    global $styleVar, $style, $media_mobile_portrait_geral, $media_desktop_landscape_geral;

    $tabs = [];
    $content = [];
    $check = [];
    
    $length = count($array);

    if(empty($array) || $length == 0) { 
        $tabs[] = "<label for='tabXXX'>Tab</label>";
        $content[] = "<input class='tabsBt' name='tabs' type='radio' id='tabXXX' checked>
                      <div class='content' id='cntXXX'><p>Conteúdo</p></div>";
        $check[] = "input#tabXXX:checked ~ #cntXXX";
    }
    else {
        for ($i = 0; $i < $length; $i++) {

            // LABEL
            $tabs[] = "<label for='tab{$i}'>{$array[$i]['title']}</label>";

            // INPUT + CONTEÚDO
            $content[] = "
                <input class='tabsBt' name='tabs' type='radio' id='tab{$i}' " . ($i == 0 ? 'checked' : '') . ">
                <div class='content' id='cnt{$i}'>
                    {$array[$i]['content']}
                </div>
            ";

            // CONTROLE DE EXIBIÇÃO
            $check[] = "input#tab{$i}:checked ~ #cnt{$i}";

            $style[] = ".tabs:has(#tab{$i}:checked) .labelsTab label[for='tab{$i}'] {
                opacity: 0.3;
                font-weight: 600;
                transform: scale(1.05);
            }";
        }
    }
    $ArrLen = 100 / max(1, $length) . "%";
    $styleVar[] = sprintf("
        --ArrLen:%s;
    ", $ArrLen);

     $style[] = file_get_contents( ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tabs.css" );
     /*
    $style[] = "
        .tabs {
            width:100%;
            display:grid;
            grid-template-rows:auto 1fr;
            grid-template-columns:1fr;
            position:relative; 
            overflow: visible;
        }
        .labelsTab { 
            width:100%;
            height: fit-content;
            display:grid;
            grid-auto-flow:column;
            grid-auto-columns: 1fr; 
            position: relative; background: {$bg}; 
            border-radius:0.9rem 0.9rem 0 0;
            overflow: hidden;
        }
        .tabs .labelsTab label { 
            padding:6%; font-size:1.2rem; font-weight:bold; 
            text-align:center; cursor:pointer;
            transition: opacity 0.3s; z-index: 2; 
        }
        
        .tab-indicator {
            position: absolute;
            top: 0; bottom: 0;
            width: " . (100 / max(1, $length)) . "%;
            background-color: #69696942; 
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
            pointer-events: none;
        }

        .tabs .tabsBt { display:none; }
        .tabs .content {
                    min-height: 100%;
                    display:none;
                    padding:2.1vh;
                    border:solid 1px {$bg};
                    text-align:justify;
                }
    */

    $style[] = implode(', ', $check) . "{
                display:block; 
            }";
    
    $media_mobile_portrait_geral[] = ".tabs .content{font-size:1rem;}";

    $media_desktop_landscape_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/desktopL/tabs.css");

    return "
    <div class='tabs'>
        <div class='labelsTab'>
            " . implode('', $tabs) . "
        </div>
        " . implode('', $content) . "
    </div>";
}