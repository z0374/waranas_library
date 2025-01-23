<?php
//variáveis globais vazias.
$bgbody='';/*plano de fundo do body(padrão = branco)*/      $title='';/*titulo da página(padão ='')*/      $favicon='';/*icone da página (padrão = padrão do navegador)*/

//arrays de concatenação vazio.
$script=[]; $header=[]; $main=[];   $footer=[]; $body=[];

//arrays de concatenação pré preenchidos.
$fscript=[//funções genéricas e prê criadas.
      "function slideshow(item,last,id){
      slideshow.cnt = slideshow.cnt || {}; slideshow.cnt[id] = (slideshow.cnt[id] || 0);
      document.getElementById(id).scrollTo({      left: item * slideshow.cnt[id], behavior: 'smooth'   }); 
      slideshow.cnt[id] = (slideshow.cnt[id] + 1) % last;   }",

      "document.querySelector('.hamburguer').addEventListener('click', (event) => {
        if (event.target.tagName === 'SUMMARY') {
            const clickedDetail = event.target.parentElement;
            const detailsElements = document.querySelectorAll('.hamburguer details');

            detailsElements.forEach((detail) => {
                // Fechar todos os detalhes que não são o clicado
                if (detail !== clickedDetail) {
                    detail.open = false;
                }});}});"
      ];

$style=[//estilo da página.
            "*{font-family:arial, sans serif;border:none;margin:0;padding:0;box-sizing:border-box;text-decoration:none;box-sizing:border-box;justify-content:center;outline:none;overflow:hidden;}",
            "body{width:100vw;height:100vh;display:flex;flex-direction:column;justify-content:space-between;{$bgbody}}",
            "main{width:100vw;height:75vh;}",
            "button:hover,a:hover,.hamburguer summary:hover{opacity:0.6;}a,button{cursor:pointer;color:#787878;}footer{width:100%;min-height:18vh;text-align:center;display:flex;flex-direction:column;}"];

$head=[//configurações da página.
      "<title>{$title}</title><meta name='viewport' content='width=device-width,initial-scale=1.0'><link rel='icon' href='{$favicon}'><script src='https://kit.fontawesome.com/b42a2f6a22.js' crossorigin='anonymous'></script>"
      ];

//função de inicialização e exibição
function html(){global $head,$style,$body,$header,$main,$footer,$script,$fscript; echo $html = "<!DOCTYPE html><html><head>".implode('', $head)."<style>".implode('', $style)."</style></head><body><header>".implode('', $header)."</header><main>".implode('', $main)."</main><footer>".implode('', $footer)."</footer>".implode('', $body)."<script>".implode('',$script).implode('',$fscript)."</script></body></html>";}


//função construtora.
function doIt($option,$adition,$property,$content){ 
      if($option == 'A'){return "<{$adition} {$property}>{$content}</{$adition}>";  }//retorna uma tag html completa com os parâmetros necessários
      if($option == 'B'){return "{$adition}"; }//retorna conteúdo livre
}
function menu($array,$bg){ //$array:define o array dos itens do menu; $bg:define o background do menu.
//requer array com as seguintes chaves=url-content      
      $nav=[]; //array das tags ancora.
      global $style; //$style:global que adiciona conteúdo ao style.
            
      $style[]= "#navegation{width:100%;height:3vh; font-size:0.9vw;background-color:{$bg};display:flex;align-items:center;justify-content:space-between;padding:0 1.2vw;}";//style do menu
      $length = count($array); //conta os itens do array
      for($i=0;$i < $length;$i++){ $nav[] = "<a id='menu{$i}' href='{$array[$i]['url']}'>{$array[$i]['content']}</a>";     }//itera os itens do array e transforma em tags ancora.

      return "<nav id='navegation'>".implode('', $nav)."</nav>"; //retorna o menu.
      
}



function listing($array){
      $footer='';$ul='';
      global $style;
      $length = count($array);
      $style[]= doIt('B'," #listing{display:flex;flex-direction:column;color:#21ffff;font-size:120%;}#listing h2{font-size:90%;font-weight:bold;}#listing a{font-size:66%;color:}#a1{cursor:auto;font-weight:bold;}#a1{text-decoration:underline;}",'','');
      for($v=0;$v<$length;$v++){$ul .= "<{$array[$v]['tag']} id='a{$v}' href={$array[$v]['href']}>{$array[$v]['content']}</{$array[$v]['tag']}>";}
      return "<ul id='listing'>".implode('', $ul)."</ul>";
      
}

function sliding($direction,$array,$id,$bg){
      $isld='';
      global $style;
      $length = count($array);
      $stylesDir=[
      'top'=>'.sld{width:100vw;height:15vh;top:-15vw;left:0;transform:translateY(0);border-radius:0 0 0 0;}.sld:target{transform: translateY(15vw);}',
      'bottom'=>'.sld{width:100vw;height:15vh;top:0;left:0;transform:translateY(100vh);border-radius:1.5vw 1.5vw 0 0;}.sld:target{transform: translateY(85vh);}',
      
      'left'=> '.sld{width:15vw;height:100vh;top:0;left:-15vw;transform:translateX(0);border-radius:0 1.5vw 0 0;}.sld:target{transform: translateX(15vw);}',
      'right'=> '.sld{width:15vw;height:100vh;top:0;left:0;transform:translateX(100vw);border-radius:1.5vw 0 0 0;}.sld:target{transform: translateX(85vw);}'
];
  $style[]= ".sld{display:flex;padding:1.5vw;position:fixed;flex-flow:column wrap;justify-content:space-around;transition:transform 0.6s ease;background-color:rgba(253,253,253,1);border:solid 0.03vw {$bg};border-top:solid 2.1vw {$bg};}
                  .sld:target{transition:transform 0.6s ease;}.sld a{font-weight:bold;}".$stylesDir[$direction].
                  ".sld #{$id}".($length-1)."{background-color:#EAC7EB;padding:0.6vw 4.2vw;border-radius:0.3vw;}";

      for($v=0;$v<$length;$v++){$isld .= "<a id='{$id}{$v}' href='{$array[$v]['href']}'>{$array[$v]['content']}</a>";}
      return "<div id='{$id}' class='sld'>".implode('', $isld)."</div>";
}

function modal($id,$array,$color){
      $mdl='';$imdl='';$item='';$linha='';
      global$style;
      $length = count($array);
      $bt = $length-1;
      $style[]= ".mdl:target{visibility:visible;opacity:1;}
                  .mdl{width:100vw;height:100vh;visibility:hidden;opacity:0;padding:6vh;display:flex;background-color:#0000009b;position:fixed;transition:.3s;}
                  .cmdl{width:24%;height:69%;border-radius:1.5vw; background-color:white; display:flex;flex-direction:column;justify-content:space-evenly;align-items:center;}#{$id} .cmdl{border-top: solid 3vw {$color};}
                  .mbt{padding:0.6vw 5.1vw;border-radius:0.3vw;}#i{font-size:0.6vw;}#itm{padding:1.2vh 0.9vw;display:flex;justify-content:space-between}
                  .content{width:81%;height:60%;overflow:auto;font-size:1.2vw;}
                  #{$id}{$bt}{background-color:#EAC7EB;padding:0.6vw 4.2vw;border-radius:0.3vw;background-color:{$color};}";
      for($v=0;$v<$length-1;$v++){
            $item = "<a id='{$id}{$v}' href={$array[$v]['href']}>{$array[$v]['content']}</a>";
                  if(array_key_exists('lnk', $array[$v])){$item .= "<a id='{$id}i{$v}' href='{$array[$v]['lnk']}' target='blank'><i id='i'class='fa-solid fa-arrow-up-right-from-square' aria-hidden='true'></i></a>";  }
            $linha .= "<span id='itm'>{$item}</span>";  }

      $imdl .= "<div class='content'>{$linha}</div>";
            $imdl .= "<a class='mbt' href='#' id='{$id}{$bt}'>{$array[($length-1)]['content']}</a>";
            $mdl = "<div class='cmdl'>{$imdl}</div>";            
            return "<div id='{$id}' class='mdl'>".implode('', $mdl)."</div>";  

}

function search($tm){
      global $style;

      $style[]= doIt('B',"#search{width:calc({$tm}% + 18%);height:calc({$tm}% + 1%);display:flex;border:solid 0.06vh black;border-radius:1.5vw 3vw 3vw 1.5vw;display:flex;justify-content:end;flex-shrink: 1;background-color:white;}

                  #search input{width:72%;height:100%;font-size:210%;border-radius:0.9vh;}

                  #search button{width:21%;border-radius:3vh;display:flex;align-items:center;cursor:pointer;}

                  #search a{width:3%;}      #search i{font-size:99%;}" ,'','');

      $campo = doIt('B','<input id="busca" placeholder="Pesquise..." name="pesquisa" value="" type="text">','','');

      $btp = doIt('A','button','id="busque"','<i class="fas fa-search fa-lg"></i>');

      return doIt('A','form','id="search"'.$campo.'<a href="?"></a>' .$btp);

}
function tags($tm,$texto,$img){
      $tag='';
      global $style;
      $style[]= "#tags{width:calc({$tm}% + 3%);height:calc({$tm}% + 1%);border-radius:calc({$tm}vw - 0.5 + 100);border:solid 0.03vw black;display:flex;}
                  #tags p{width:60%;height:100%;}#tags a{width:40%;height:100%;background:url({$img}) no-repeat;background-size:contain;background-position:center;}";
      return "<div id='tags'><p>{$texto}</p><a></a></div> ";
}

function slideshow($id,$array,$tm,$auto){
      global $style,$script;
      $items=[];
      $length = count($array);
      $style[] = " .slideshow{width:calc({$tm}vw*2);height:calc({$tm}vw*1);position:relative;border-radius:calc({$tm}*0.06vw);border:solid 0.03vh black;}
                  .items_slideshow{width:100%;height:100%;display:flex;justify-content:flex-start;overflow:auto;scrollbar-width:none;scroll-snap-type:x mandatory;transition: transform 0.9s ease;}.slideshow::-webkit-scrollbar{display:none;}
                  .item{min-width:100%;height:100%;scroll-snap-align:center;cursor:default;}.item:hover{opacity:1;}
                  .slideshow #bt{width:calc({$tm}vw*2 / 9);max-height:calc({$tm}vw*1);right:0;top:0;position:absolute;z-index:10;font-size:calc({$tm}vw*1/3);text-align:center;line-height:calc({$tm}vw*1);opacity:0;background-color:rgba(0,0,0,0.06);}.slideshow #bt:hover{opacity:0.3; transition:0.9s;cursor:pointer;}
                  ";
      for($i=0;$i<$length;$i++){
            $items[] = "<a href='{$array[$i]['url']}' id='{$id}{$i}' class='item' target='blank' title=''></a>";
            $style[] ="#{$id}{$i}{background:url({$array[$i]['image']}) no-repeat;background-size:contain;background-position:center;}";}
 
      $script[] = "
      function slideshow{$id}(){
      let item = document.querySelector('#{$id}').offsetWidth;
      let last = {$length};
      let id = '{$id}';
      slideshow(item,last,id);
      }";
      if($auto !== 0){$script[]= "setInterval(slideshow{$id}, {$auto} *1000);";}

      return "<div class='slideshow'><div id='{$id}' class='items_slideshow'>".implode('', $items)."</div><button id='bt' onclick='slideshow{$id}()'> > </button></div>";
}

function hamburguer($mode,$array,$tm){
      global $style,$script;
      $hamburguer=[];     $length = count($array);

      $style[]=".hamburguer #dt".($length-1)."{border-radius:0 0 0.24vw 0.24vw;}.hamburguer #dt0{border-radius:0.24vw 0.24vw 0 0;}.hamburguer summary{width:calc({$tm}vw*9);background:rgba(90,90,90);padding:3% 0;border-radius:0.24vw;font-weight:bold;list-style:none;font-size:calc({$tm}vw /2.5);text-align:center;cursor:pointer;}.hamburguer details[open] summary{border-radius:0.24vw 0.24vw 0 0;}";
      if($mode == 'accordeon'){
            $style[] ="
                  .hamburguer details{width:calc({$tm}vw*9);position:relative;background:rgba(90,90,90);border-top:solid 0.06vw rgba(30,30,30,0.6);}
                  .hamburguer details p{width:calc({$tm}vw*9);position:relative;background:rgba(150,150,150);padding:0 calc({$tm}vw*0.15);}
                              ";}
      if($mode == 'dropdown'){
            $style[] ="
                  .hamburguer details{display:flex;flex-flow:row wrap;justify-content:space-around;border-top:solid 0.03vw #f3f3f3;border-radius:0.24vw;}
                  .hamburguer details p{width:calc({$tm}vw*15);position:absolute;background:rgba(150,150,150);padding:0 calc({$tm}vw*0.15);}
                              ";}
            
      
      for($i=0;$i<$length;$i++){$hamburguer[] = "<details id='dt{$i}'><summary>".$array[$i]['title']."</summary><p>".$array[$i]['content']."</p></details>";}     

      return "<div class='hamburguer'>".implode('', $hamburguer)."</div>";
}

function tabs($array,$bg) {
    $tabs = [];$content = [];$check=[];
    global$style;
    $length = count($array);

    for ($i = 0; $i < $length; $i++) {
        $tabs[] = "<input class='tabsBt' name='tabs' type='radio' id='tab{$i}'><label for='tab{$i}'>{$array[$i]['title']}</label>";
        $content[]= "<div class='content' id='cnt{$i}'>{$array[$i]['content']}</div>";
        $check[]= "input#tab{$i}:checked ~ #cnt{$i},";}

            $style[] = "  main{display:flex;align-items:center;}
                  .tabs{width:81%;display:grid;grid-template-columns:repeat({$length}, 1fr);grid-template-rows: auto;border-radius:0.6vh 0.6vh 0 0;}
                  .tabs label{width:100%;grid-row:1;padding:0.9vw;font-size:2.1vh;font-weight:bold;background:$bg;text-align:center;cursor:pointer;}
                  input[type='radio']:checked + label,label:hover{opacity:0.72;}
                  .tabs .tabsBt{display:none;}
                  .tabs .content{width:100%;visibility:hidden;padding:2.1vh;grid-column:1/-1;grid-row:2;border-top:solid 0.12vh $bg;cursor:text;border:solid 1px $bg;}".
                  implode('', $check)."p{visibility:visible;}";

      
    return "<div class='tabs'>".implode('', $tabs).implode('', $content). "</div>";
}
