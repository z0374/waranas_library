<?php
function tagList($id, $array, $tm) {
    global $style, $mobile;
    $tag = [];
    $length = count($array);

    // Estilos 100% dinâmicos
    $style[] = "
        .tagList{width:auto;}
        .{$id}{width:calc({$tm}vw*7.8);height:calc({$tm}vw*1.5);display:flex;justify-content:start;margin:1.5% 0;border-top:solid 0.12vh black;padding:calc({$tm}vw/6) 0.18%;overflow:hidden;}
        .{$id} .img{width:calc({$tm}vw*2.4);height:100%;overflow:hidden;background-repeat:no-repeat;background-size:contain;background-position:center;transition: background 0.9s ease;}
        textarea{width:81%;text-align:justify;padding:0 1.2%;font-size:0.7rem;resize:none;background:none;overflow:hidden;}
    ";
    
    $mobile[] = "
        .tag{width:calc({$tm}vw*22.1);height:calc({$tm}vh*6);padding:1.2% 0;margin:0;}
        .tag .img{width:calc({$tm}vw*3);height:calc({$tm}vw*1.5);background-repeat:no-repeat;background-position:center;transition: background 0.9s ease;}
        .tag textarea{min-height:calc({$tm}vh*5.1);font-size:0.8rem;}
    ";
    
    for ($i = 0; $i < $length; $i++) {
        $tag[] = "<a href={$array[$i]['url']} aria-label='link clicável'><div class='tag {$id}'><div class='img' data-bg='url({$array[$i]['image']})' loading='lazy'></div></a><textarea readonly rows='12' aria-label='{$array[$i]['text']}'>{$array[$i]['text']}</textarea></div>";
    }

    return "<div class='tagList' id='{$id}'>" . implode('', $tag) . "</div>";
}