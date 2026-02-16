<?php

function slideshow($id, $array, $tm, $auto)
{
    global $style, $script, $fscript, $mobile, $css_files, $script_files;

    // Inclusão de bibliotecas (mantendo seu padrão)
    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/slideshow.css';
    if (!in_array($component_css, $css_files)) { $css_files[] = $component_css; }

    $items = [];
    $length = count($array);

    // CSS com Pseudo-elementos e Scroll Snap para "travar" no slide
    $style[] = "
        .slideshow, #d{$id} {
            width: calc({$tm}vw * 2);
            height: calc({$tm}vw * 1);
            position: relative;
            border-radius: calc({$tm} * 0.06vw);
            overflow: hidden;
            background: #000;
        }
        .items_slideshow {
            display: flex;
            flex-wrap: nowrap;
            width: 100%;
            height: 100%;
            overflow-x: auto; /* Permite scroll */
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory; /* Força o slide a parar no início de cada item */
            -ms-overflow-style: none;  /* Esconde scrollbar no IE/Edge */
            scrollbar-width: none;  /* Esconde scrollbar no Firefox */
        }
        .items_slideshow::-webkit-scrollbar { display: none; } /* Esconde scrollbar no Chrome/Safari */

        .slide-wrapper {
            flex: 0 0 100%;
            width: 100%;
            height: 100%;
            position: relative;
            scroll-snap-align: start; /* Ponto de parada do scroll */
        }

        .slide-link {
            display: block;
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        /* Camada Blur via After/Before */
        .slide-link::before {
            content: '';
            position: absolute;
            top: -10%; left: -10%; width: 120%; height: 120%;
            background-image: var(--bg-slide);
            background-size: 210%;
            background-position: center;
            filter: blur(15px) brightness(0.8);
            z-index: 1;
        }

        /* Camada Imagem Nítida */
        .slide-link::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: var(--bg-slide);
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            z-index: 2;
        }

        .slideshow .nav-bt {
            width: 12%; 
            height: 100%;
            font-size: 2rem;
            position: absolute; 
            top: 0; 
            z-index: 10;
            border: none; 
            background: linear-gradient(to right, rgba(0,0,0,0.4), transparent);
            color: #fff;
            cursor: pointer;
            transition: 0.3s;
            opacity: 0;
        }
        #next{$id} { right: 0; background: linear-gradient(to left, rgba(0,0,0,0.4), transparent); }
        .slideshow:hover .nav-bt { opacity: 1; }
    ";
    
    $mobile[] = "#d{$id} { width: 90vw; height: 50vw; }";

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
        <button id='prev{$id}' class='nav-bt' onclick='moveSlide{$id}(-1)'> &#10094; </button>
        <div id='{$id}' class='items_slideshow'>" . implode('', $items) . "</div>
        <button id='next{$id}' class='nav-bt' onclick='moveSlide{$id}(1)'> &#10095; </button>
    </div>";
}