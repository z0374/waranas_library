<?php

function slideshow($id, $array, $tm, $auto)
{
    global $styleVar, $style, $script, $fscript, $mobile, $css_files, $script_files, $media_mobile_portrait_geral, $media_mobile_landscape_geral, $media_tablet_landscape_geral, $media_tablet_portrait_geral;

    // Inclusão de bibliotecas (mantendo seu padrão)
    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/slideshow.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
        $tm_limpo = (int) $tm;
        $styleVar[] = "--slideshow-tm: " . $tm_limpo . ";";
    }

    $items = [];
    $length = count($array);
    // CSS com Pseudo-elementos e Scroll Snap para "travar" no slide
    $style[] = "
    #d{$id} {
        border-radius: calc(" . $tm . "cqw * 0.06vw);
    }

    /* Estilização específica do botão por ID se necessário, 
       mas as posições agora usam classes genéricas */
    #prev{$id} { left: 0; }
    #next{$id} { right: 0; }
";

    $media_mobile_portrait_geral[] = "#d{$id} { width: 90vw; height: 50vw; }";
    $media_mobile_landscape_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/mobileL/slideshow.css");

    $media_tablet_portrait_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tabletP/slideshow.css");
    $media_tablet_landscape_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tabletL/slideshow.css");

    for ($i = 0; $i < $length; $i++) {
        $img = $array[$i]['image'];
        $url = $array[$i]['url'];
        $items[] = "
            <div class='slide-wrapper'>
                <a href='{$url}' target='_blank' class='slide-link' style='--bg-slide: url(\"{$img}\");'></a>
            </div>";
    }

    // Lógica de Movimentação via Scroll
    $script[] = "
    function moveSlide{$id}(dir) {
        const el = document.getElementById('{$id}');
        const slideWidth = el.offsetWidth;
        const maxScroll = el.scrollWidth - slideWidth;
        
        // Se chegar ao fim e clicar para avançar, volta ao início (loop)
        if (dir === 1 && el.scrollLeft >= maxScroll - 5) {
            el.scrollTo({ left: 0, behavior: 'smooth' });
        } 
        // Se estiver no início e clicar para voltar, vai ao fim
        else if (dir === -1 && el.scrollLeft <= 5) {
            el.scrollTo({ left: maxScroll, behavior: 'smooth' });
        } 
        else {
            el.scrollBy({ left: slideWidth * dir, behavior: 'smooth' });
        }
    }";

    if ($auto !== 0) {
        $script[] = "setInterval(() => moveSlide{$id}(1), {$auto} * 1000);";
    }

    return "
        <div class='slideshow' id='d{$id}'>
            <button id='prev{$id}' class='nav-bt nav-bt-prev' onclick='moveSlide{$id}(-1)'> &#10094; </button>
            <div id='{$id}' class='items_slideshow'>" . implode('', $items) . "</div>
            <button id='next{$id}' class='nav-bt nav-bt-next' onclick='moveSlide{$id}(1)'> &#10095; </button>
        </div>";
}