<?php
function search($tm, $lnksData, $htmlResult) {
    global $style, $script, $mobile, $lupa, $css_files, $script_files;

    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/search.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/search.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    $jsonData = json_encode($lnksData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    $script[] = 'const jsonData=' . $jsonData . '; const data =JSON.parse(JSON.stringify(jsonData));';

    // Estilos dinâmicos
    $style[] = "#search{width: calc({$tm}% + 18%);height:calc({$tm}% + 3%);}";
    $mobile[] = "
        #search{width:60%;min-height:44px;}
        #search input{width:57%;}
        #search button{width:24%;}
    ";
    $lupa = getSVG("lupa", "lupaSVG");
    $safeHTMLResult = strval($htmlResult);
    $campo = '<input aria-label="barra de pesquisa" id="busca" placeholder="Pesquise..." type="text">';
    return "<span id='search'>{$campo}<a aria-label='resetar pesquisa' href='?'></a><button aria-label='Buscar' id='busque' onclick='pesquisar(data, `text`, `$safeHTMLResult`)'>" . $lupa . "</button></span>";
}