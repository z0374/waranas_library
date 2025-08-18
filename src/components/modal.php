<?php
function modal($id, $array, $color) {
    global $style, $mobile, $body, $css_files;

    $component_css = ROOT_PATH . '/public/assets/css/components/modal.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    if (is_array($array)) {
        $length = count($array);
    } else {
        $length = 2;
    }
    $bt = $length - 1;

    // Estilos dinâmicos
    $style[] = "
        #modal-toggle{$id}:checked + #{$id}Mdl{visibility:visible;opacity:1;}
        #{$id} .cmdl{border-top: solid 6vh {$color};}
        #{$id}{$bt}{background-color:#EAC7EB;padding:3% 15%;border-radius:0.9vh;background-color:{$color};}
    ";
    $mobile[] = ".cmdl{width:90%;height:69%;}";

    $linha = '';
    if (is_array($array)) {
        for ($v = 0; $v < $length - 1; $v++) {
            $item = "<a id='{$id}{$v}' href={$array[$v]['href']}>{$array[$v]['content']}</a>";
            if (array_key_exists('lnk', $array[$v])) {
                $item .= "<a id='{$id}i{$v}' href='{$array[$v]['lnk']}' target='blank'><i id='i'class='fa-solid fa-arrow-up-right-from-square' aria-hidden='true'></i></a>";
            }
            $linha .= "<span id='itm'>{$item}</span>";
        }
    } else {
        $linha = $array;
    }

    $imdl = "<div class='content'>{$linha}</div>";
    if (is_array($array)) {
        $imdl .= "<a class='mbt' href='/' id='{$id}{$bt}'>{$array[($length-1)]['content']}</a>";
    } else {
        $imdl .= "<label class='closeX' for='modal-toggle{$id}'>&times;</label>";
    }

    $mdl[] = "<label class='closeModal' for='modal-toggle{$id}'></label><div class='cmdl' onclick='event.stopPropagation()'>{$imdl}</div>";
    $body[] = "<input type='checkbox' id='modal-toggle{$id}' class='modal-toggle' /><div id='{$id}Mdl' class='mdl'>" . implode('', $mdl) . "</div>";
    return '<label id="' . $id . '" for="modal-toggle' . $id . '" class="openModal">' . $id . '</label>';
}