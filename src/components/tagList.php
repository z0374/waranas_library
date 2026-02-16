<?php
function tagList($id, $array, $tm) {
    global $style, $mobile;
    $tag = [];
    $length = count($array);

    // Estilos 100% dinâmicos
    $style[] = "
        .tagList { width:100%; padding:0 12%; }
        
        /* O Todo agora é uma Section */
        .tag-section { 
            width:calc({$tm}vw*7.8); display:flex; justify-content:start; 
            margin:1.5% 0; border-top:solid 0.12vh black; 
            padding:calc({$tm}vw/6) 0.18%; overflow:hidden; 
        }

        /* O Link agora é a Imagem com o Blur */
        .tag-link {
            position:relative;
            width:100%; height:100%; /* Ajuste conforme a necessidade do seu layout */
            display:block;
            overflow:hidden;
            background-color: #000;
            text-decoration:none;
        }

        /* Camada 1: O fundo borrado */
        .tag-link::before {
            content:'';
            position:absolute;
            top:-20px; left:-20px; right:-20px; bottom:-20px;
            background-image: var(--bg-img);
            background-size: 210%;
            background-position: top;
            background-repeat: no-repeat;
            filter: blur(10px) brightness(0.6);
            z-index: 1;
        }

        /* Camada 2: A imagem nítida (frente) */
        .tag-link::after {
            content:'';
            position:absolute;
            top:0; left:0; width:100%; height:100%;
            background-image: var(--bg-img);
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            z-index: 2;
            transition: transform 0.5s ease;
        }

        /* Efeito de hover opcional na imagem */
        .tag-link:hover::after { transform: scale(1.05);}
        .tag-link:hover {opacity:1;}

        p { 
            width:70%; text-align:justify; padding:0 1.2%; 
            font-size:0.7rem; resize:none; background:none; 
            border:none; overflow:hidden; outline:none;
        }
    ";
    
    $mobile[] = "
        .tag-section { display:flex; flex-flow:column nowrap; width:100%; padding:3% 0;}
        .tag-link { width:100%; height:60cqw; }
        p { width:100%; min-height:30cqw; font-size:0.8rem; padding:6% 0;}
    ";
    
    for ($i = 0; $i < $length; $i++) {
        $imgUrl = $array[$i]['image'];
        
        $tag[] = "
            <section class='tag-section {$id}'>
                <a href='{$array[$i]['url']}' 
                   class='tag-link' 
                   aria-label='Ver detalhes da imagem' 
                   style='--bg-img: url({$imgUrl});' 
                   data-bg='url({$imgUrl})'>
                </a>
                <p readonly rows='12' aria-label='Descrição'>{$array[$i]['text']}</p>
            </section>"; 
    }

    return "<div class='tagList' id='{$id}'>" . implode('', $tag) . "</div>";
}