<?php
function grid($id, $grids, $title) {
    global $style, $mobile; // Acessa as variáveis globais de estilo
    $section = [];

    // Os estilos são mantidos aqui por serem específicos para o ID do grid
    $style[] = "
        #{$id} {width:81vw;display:flex;flex-flow:row wrap;align-items:center;justify-content:space-around;padding:0.9rem 0;margin:1.5rem;border-radius:0.6rem;box-shadow: 0px 0px 10px rgba(90, 90, 90, 0.3);}
        #{$id} h1{width:100%;text-align:center;font-size:2.4rem;text-decoration:underline;grid-column:1;}
        #{$id} div{width:27rem;height:30rem;margin-top:0.9rem;box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);display:flex;flex-direction:column;padding:0.6rem 1.2rem;justify-content:space-between;border-radius:0.9rem;}
        #{$id} figure{width:100%;height:100%;}
        #{$id} p{display:none;}
    ";

    $style[] = "
        @media(max-width:990px) and (orientation:landscape){
            #{$id} {overflow-y:visible;overflow-x:scroll;width:93vw;min-height:36rem;padding:0 1.5rem;box-sizing:border-box;display:grid;flex-wrap:nowrap;justify-content:flex-start;justify-items:start;gap:1.5rem;grid-template-rows:1fr 1fr;scroll-snap-type:x mandatory;scroll-behavior:smooth;}
            #{$id} h1{position:sticky;top: 0;left:0;z-index:3;grid-row:1;}
            #{$id} div{width:81vw;grid-row:2;scroll-snap-align:center;}
        }
    ";
    
    $mobile[] = "
        #{$id} {overflow-x:scroll;width:99vw;min-height:84vh;padding:0 4.2vw;box-sizing:border-box;display:flex;flex-flow:row nowrap;justify-content:flex-start;align-items:start;gap:3rem;scroll-snap-type:x mandatory;scroll-behavior:smooth;}
        #{$id} h1{display:none;visibility:hidden;}
        #{$id} div{min-width:90vw;height:72vh;scroll-snap-align:center;}
    ";
    
    // Supondo que $grids seja um array para configurar os itens, mas o loop original era fixo para 6
    for ($i = 0; $i < 6; $i++) {
        $section[] = "<div><figure id='img{$id}{$i}' ></figure><h2><button class='vr'>ver mais...</button></h2><p></p></div>";
    }

    return "<section id='{$id}'><h1>{$title}</h1>" . implode('', $section) . "</section>";
}