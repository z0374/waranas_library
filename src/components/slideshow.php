<?php

function slideshow($id, $array, $tm, $auto, $element = 'default')
{
    global $styleVar, $style, $script, $css_files, $script_files;

    // Inclusão de bibliotecas
    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/slideshow.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
        $styleVar[] = "--slideshow-tm: " . (int)$tm . ";";
    }
    
    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/slideshow.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    $items = [];
    $length = count($array);
    
    // CSS Dinâmico (Arredondamento baseado no tm)
    $style[] = "#d{$id} { border-radius: calc({$tm}cqw * 0.06); }";

    for ($i = 0; $i < $length; $i++) {
        $source = $array[$i]['media'];
        $url = $array[$i]['url'];
        $innerContent = '';
        $anchorAttributes = "class='slide-link' target='_blank'";

        switch ($element) {
            case 'image':
                $innerContent = "<img src='{$source}' style='width:100%; height:100%; object-fit:cover;' loading='lazy'>";
                break;
            case 'video':
                $innerContent = "<video src='{$source}' autoplay muted loop playsinline style='width:100%; height:100%; object-fit:cover;'></video>";
                break;
            case 'audio':
                $innerContent = "<audio src='{$source}' controls style='width:100%;'></audio>";
                break;
            case 'iframe':
                $innerContent = "<iframe src='{$source}' frameborder='0' allowfullscreen loading='lazy'></iframe>";
                break;
            case 'embed':
                $innerContent = "<embed src='{$source}' style='width:100%; height:100%;'>";
                break;
            case 'default':
            default:
                $anchorAttributes .= " style='--bg-slide: url(\"{$source}\");'";
                $innerContent = ""; 
                break;
        }

        $items[] = "
            <div class='slide-wrapper'>
                <a href='{$url}' {$anchorAttributes}>{$innerContent}</a>
            </div>";
    }

    // Função local para chamar a global
    $script[] = "
    function moveSlide{$id}(dir) {
        if (typeof slideshow === 'function') {
            slideshow('{$id}', dir);
        }
    }";

    // Auto-play
    if ($auto > 0) {
        $script[] = "setInterval(() => moveSlide{$id}(1), {$auto} * 1000);";
    }

    // Estrutura HTML
    // IMPORTANTE: Adicionado o data-index='0'
    return "
        <div class='slideshow' id='d{$id}'>
            <button id='prev{$id}' class='nav-bt nav-bt-prev' onclick='moveSlide{$id}(-1)'> &#10094; </button>
            <div id='{$id}' class='items_slideshow' data-index='0'>" . implode('', $items) . "</div>
            <button id='next{$id}' class='nav-bt nav-bt-next' onclick='moveSlide{$id}(1)'> &#10095; </button>
        </div>";
}