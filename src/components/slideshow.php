<?php
function slideshow($id, $array, $tm, $auto)
{
    global $style, $script, $fscript, $mobile, $css_files, $script_files;

    // Caminhos das bibliotecas (mantendo sua lógica de diretórios)
    $component_css = ROOT_PATH_WARANAS_LIB . '/public/assets/css/components/slideshow.css';
    if (!in_array($component_css, $css_files)) { $css_files[] = $component_css; }

    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/slideshow.js';
    if (!in_array($component_script, $script_files)) { $script_files[] = $component_script; }

    $items = [];
    $length = count($array);

    // Estilos dinâmicos - O segredo do 'rolar' está no flex-wrap: nowrap
    $style[] = "
        .slideshow, #d{$id} {
            width: calc({$tm}vw * 2);
            height: calc({$tm}vw * 1);
            position: relative;
            border-radius: calc({$tm} * 0.06vw);
            overflow: hidden;
        }
        .items_slideshow {
            display: flex;
            flex-wrap: nowrap; /* Impede que os itens quebrem linha (evita piscar) */
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            scroll-behavior: smooth; /* Suavidade nativa do browser */
        }
        .slide-wrapper {
            flex: 0 0 100%; /* Força cada slide a ter exatamente 100% da largura */
            width: 100%;
            height: 100%;
            position: relative;
        }
        .slideshow .nav-bt {
            width: calc({$tm}vw * 2 / 8); 
            height: 100%;
            font-size: calc({$tm}vw * 1 / 4); 
            position: absolute; 
            top: 0; 
            z-index: 45;
            border: none; 
            cursor: pointer;
            transition: 0.3s;
            opacity: 0;
        }
        .slideshow:hover .nav-bt { opacity: 0.6; }
        .slideshow .nav-bt:hover { background: rgba(0,0,0,0.4); opacity: 1; }
        #prev{$id} { left: 0; }
        #next{$id} { right: 0; }
    ";
    
    $mobile[] = "#d{$id} { width: calc({$tm}vw * 6); height: calc({$tm}vw * 3); }";

    for ($i = 0; $i < $length; $i++) {
        $img = $array[$i]['image'];
        $url = $array[$i]['url'];
        
        $items[] = "
            <div class='slide-wrapper'>
                <a href='{$url}' target='_blank' style='display:block; width:100%; height:100%;'>
                    <aside class='slideItemImg' id='img{$id}{$i}' style='background: url({$img}) no-repeat center; background-size: contain; position: absolute; width:100%; height:100%; z-index: 31;'></aside>
                    <aside class='slideBlur' id='blur{$id}{$i}' style='background: url({$img}) no-repeat center; background-size: cover; filter: blur(8px); position: absolute; width:100%; height:100%; z-index:30;'></aside>
                </a>
            </div>";
    }

    $script[] = "
    function moveSlide{$id}(dir){
        if(typeof slideshow === 'function'){
            const container = document.getElementById('{$id}');
            slideshow(container.offsetWidth, {$length}, '{$id}', dir);
        }
    }";

    if ($auto !== 0) {
        $script[] = "setInterval(() => moveSlide{$id}(1), {$auto} * 1000);";
    }

    return "
    <div class='slideshow' id='d{$id}'>
        <button id='prev{$id}' class='nav-bt' onclick='moveSlide{$id}(-1)'> < </button>
        <div id='{$id}' class='items_slideshow'>" . implode('', $items) . "</div>
        <button id='next{$id}' class='nav-bt' onclick='moveSlide{$id}(1)'> > </button>
    </div>";
}