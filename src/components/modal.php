<?php
function modal($id, $array, $color) {
    global $style, $mobile, $body, $css_files;

    $component_css = ROOT_PATH_WARANAS_LIB . '/assets/css/components/modal.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    if (is_array($array)) {
        $length = count($array);
    } else {
        $length = 2;
    }
    $bt = $length - 1;

    $lowerId = mb_strtolower($id, 'UTF-8');
    $distinctId = $lowerId . distinctDigits();
    // Estilos dinâmicos
    $style[] = "
        #modal-toggle{$distinctId}:checked + #{$distinctId}Mdl{visibility:visible;opacity:1;}
        #{$distinctId} .cmdl{border-top: solid 6vh {$color};}
        #{$distinctId}{$bt}{background-color:#EAC7EB;padding:3% 15%;border-radius:0.9vh;background-color:{$color};}
    ";
    $mobile[] = ".cmdl{width:90%;height:69%;}";

    $linha = '';


    if (is_array($array)) {
        for ($v = 0; $v < $length - 1; $v++) {
            $item = "<a id='{$distinctId}{$v}' href={$array[$v]['href']}>{$array[$v]['content']}</a>";
            if (array_key_exists('lnk', $array[$v])) {
                $item .= "<a id='{$distinctId}i{$v}' href='{$array[$v]['lnk']}' target='blank'><i id='i'class='fa-solid fa-arrow-up-right-from-square' aria-hidden='true'></i></a>";
            }
            $linha .= "<span id='itm'>{$item}</span>";
        }
    } else {
        $linha = $array;
    }

    $imdl = "<div class='content'>{$linha}</div>";
    if (is_array($array)) {
        $imdl .= "<a class='mbt' href='/' id='{$distinctId}{$bt}'>{$array[($length-1)]['content']}</a>";
    } else {
        $imdl .= "<label class='closeX' for='modal-toggle{$distinctId}'>&times;</label>";
    }

    $mdl[] = "<label class='closeModal' for='modal-toggle{$distinctId}'></label><div class='cmdl' onclick='event.stopPropagation()'>{$imdl}</div>";
    $body[] = "<input type='checkbox' id='modal-toggle{$distinctId}' class='modal-toggle' /><div id='{$distinctId}Mdl' class='mdl {$lowerId}'>" . implode('', $mdl) . "</div>";

    return '<label id="' . $lowerId . 'Label" for="modal-toggle' . $distinctId . '" class="openModal ' . $lowerId . 'Label">' . $id . '</label>';
}