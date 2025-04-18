<?php
header('Content-Type: text/html; charset=utf-8');

//variáveis globais vazias.
$bgbody=[];/*plano de fundo do body(padrão = branco)*/      $title=[];/* titulo da página(padão ='')*/      $favicon=[];/*icone da página (padrão = padrão do navegador)*/

//arrays de concatenação vazio.
$script=[
    "window.addEventListener('load', function() {
  const backgrounds = document.querySelectorAll('.img');
  backgrounds.forEach(el => {
    const bgImage = el.dataset.bg;
    el.style.backgroundImage = bgImage;
    el.style.backgroundColor = 'rgba(255,255,255,0)';
  });
});",
]; $header=[]; $main=[];   $footer=[]; $body=[];$mobile=[];$fonts=[];$styleLink=['']; $head=[];

//arrays de concatenação pré preenchidos.
$fscript=[//funções genéricas e prê criadas.
      [   "name"=>'slideshow',
          "code"=>"function slideshow(item,last,id){
      slideshow.cnt = slideshow.cnt || {}; slideshow.cnt[id] = (slideshow.cnt[id] || 0);
      document.getElementById(id).scrollTo({      left: item * slideshow.cnt[id], behavior: 'smooth'   }); 
      slideshow.cnt[id] = (slideshow.cnt[id] + 1) % last;   }"
      ],

      [   "name"=>"hamburguer",
          "code"=>"if(document.querySelector('body').classList.contains('hamburguer')){document.querySelector('.hamburguer').addEventListener('click', (event) => {
        if (event.target.tagName === 'SUMMARY') {
            const clickedDetail = event.target.parentElement;
            const detailsElements = document.querySelectorAll('.hamburguer details');

            detailsElements.forEach((detail) => {
                // Fechar todos os detalhes que não são o clicado
                if (detail !== clickedDetail) {
                    detail.open = false;
                }});}});}"
        ],
        [   'name'=>'search',
            'code'=>'function pesquisar(data) {
  const input = document.getElementById("busca").value.toLowerCase(); // Termo de pesquisa
  const result = document.querySelector("main"); // Onde os resultados serão exibidos
  result.innerHTML = ""; // Limpa os resultados anteriores

  // Função de comparação para verificar se o termo está na string de tags
  function isEqual(tags, searchTerm) {
    // Converte a string de tags em um array e verifica se o termo de pesquisa está presente
    const tagArray = tags.toLowerCase().split(","); // Converte para minúsculas e divide as tags
    return tagArray.some(tag => tag.includes(searchTerm)); // Verifica se o termo de pesquisa está em alguma das tags
  }

  // Itera sobre o array de dados
  data.forEach(item => {
    // Verificando se o termo de pesquisa está nas tags do item
    if (isEqual(item["tags"], input)) {
      const li = document.createElement("li");
        li.setAttribute("class","itms");
      const h1 = document.createElement("h1");
      h1.textContent = item["ttl"];
      const h2 = document.createElement("h2");
      h2.textContent = "("+item["lgd"]+")";
      const a = document.createElement("a");
      a.textContent = item["cnt"]; a.href=item["anc"];
      li.append(h1);
      li.append(h2);
      li.append(a);
      result.appendChild(li); // Exibe o item no resultado
    }
  });if(result.innerHTML==""){result.style.padding="30% 0";result.innerHTML="<li class=itms><h1>ELEMENTO LEXICAL INESISTENTE !</h1></li>";return;}
}'
],
[   "name"=>"sendwhats",
    "code"=>"function sendwhats(nm,mg,tl){
        let name=document.getElementById(nm).value;
        let mensage=document.getElementById(mg).value;
        const url = 'https://api.whatsapp.com/send?phone='+ tl +'&text=Sou%20*'+ name +'*%0A%0A'+ mensage;console.log(name);console.log(mensage);console.log(url);
               window.open(url, '_blank');

}"
],
[   "name"=>"imgBinary",
    "code"=>"function imgBinary(binary, id) {
    const byteArray = new Uint8Array(binary);
    const blob = new Blob([byteArray], { type: 'image/png' });
    const url = URL.createObjectURL(blob);

    let fig = document.getElementById(id);
    fig.style.background='url('+url+') no-repeat center center / contain';

}"
]
      ];

$style=[//estilo da página.
            "*{display:auto;font-family:arial,sans serif;border:none;margin:0;padding:0;box-sizing:border-box;text-decoration:none;justify-content:center;outline:none;min-width:8px;}",
            "body{width:100vw;height:auto;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;background:".implode('',$bgbody).";}",
            "button:hover,a:hover,.hamburguer summary:hover{opacity:0.6;}a,button{cursor:pointer;}
            footer{width:100%;text-align:center;display:flex;flex-direction:column;}"];


//função de inicialização e exibição
function html($tempo){
    global $lang,$head,$fonts,$style,$styleLink,$body,$header,$main,$footer,$script,$fscript,$mobile,$title,$favicon; 

        $head[]=[//configurações da página. 
            "<meta charset='utf-8'><title>".implode('',$title)."</title><meta name='viewport' content='width=device-width,initial-scale=1.0'><link rel='icon' href=".implode('', $favicon).">"
        ];
$html= "<!DOCTYPE html><html lang=".$lang."><head>".implode('', $head)."<style>".implode('', $fonts).implode('', $style)."@media(max-width:900px){".implode('', $mobile)."}</style><link rel='stylesheet' href=".$styleLink[0]."></head><body><header>".implode('', $header)."</header><main>".implode('', $main)."</main><footer>".implode('', $footer)."</footer>".implode('', $body)."<script>".implode('',$script)."</script></body></html>";

    switch ($tempo) {
    case "real-time":
        echo $html;
        break;
    case "cache":
        cachePage(normalize($title[0]),$html,'create',false);
        break;
    default:
        echo $html;
    }


}

//função construtora.
function doIt($option,$adition,$property,$content){
      if($option == 'A'){return "<{$adition} {$property}>{$content}</{$adition}>";  }//retorna uma tag html completa com os parâmetros necessários
      if($option == 'B'){return "{$adition}"; }//retorna conteúdo livre
}

function menu($array,$bg){ //$array:define o array dos itens do menu; $bg:define o background do menu.
//requer array com as seguintes chaves=url-content      
      $nav=[]; //array das tags ancora.
      global $style; //$style:global que adiciona conteúdo ao style.
            
      $style[]= ".navegation{width:100%;height:6%;font-size:1.2rem;font-weight:bolder;background-color:{$bg};display:flex;align-items:center;justify-content:space-around;padding:0 1.2vw;}
        .navegation a{padding:0.09em;}";//style do menu
      $length = count($array); //conta os itens do array
      for($i=0;$i < $length;$i++){ $nav[] = "<a id='menu{$i}' href='{$array[$i]['url']}'>{$array[$i]['content']}</a>";     }//itera os itens do array e transforma em tags ancora.

      return "<nav class='navegation'>".implode('', $nav)."</nav>"; //retorna o menu.
      
}

function btFloat($name,$lnk){
    global $body,$style;
    $body[]='<a class="btFloat" href='.$lnk.' style="position:absolute;top:0.3vw;left:0.6vw;">'.$name.'</i></a>';
    $style[]='.btFloat{opacity:0.09;}.btFloat:hover{opacity:0.9;}';
}

function listing($array){//recebe um array com as seguintes chaves. tag - href - content.
      $footer='';$ul=[];
      global $style;
      $length = count($array);
      $style[]= doIt('B'," #listing{display:flex;flex-direction:column;font-size:120%;}",'','');
      for($v=0;$v<$length;$v++){$ul[]= "<li><{$array[$v]['tag']} id='a{$v}' href={$array[$v]['href']}>{$array[$v]['content']}</{$array[$v]['tag']}></li>";}
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
      $mdl=[];$imdl='';$item='';$linha='';
      global$style,$mobile;

            $script[]="
                    const minhaDiv = document.querySelector('#".$id." .cmdl');
                    document.addEventListener('click', function(event) {
                    if (!minhaDiv.contains(event.target)) {
                    location.hash = '#';
                    }
                });
            ";
    if(is_array($array)){
      $length = count($array);}else{$length = 2;}
      $bt = $length-1;
      $style[]= "
                  .mdl:target{visibility:visible;opacity:1;}
                  .mdl{width:100vw;height:100vh;visibility:hidden;opacity:0;align-items:center;display:flex;background-color:#0000009b;position:fixed;top:0;transition:.3s;}
                  .cmdl{width:24%;height:69%;border-radius:1.5vw; position:relative; background-color:white; display:flex;flex-direction:column;justify-content:space-evenly;align-items:center;}#{$id} .cmdl{border-top: solid 6vh {$color};}
                  .cmdl .closeX{color:red;position:absolute;right:15px;top:9px;font-weight: 900;}
                  .mbt{padding:0.6vw 5.1vw;border-radius:0.3vw;}#i{font-size:0.6rem;}#itm{padding:1.2vh 0.9vw;display:flex;justify-content:space-between}
                  .content{width:81%;height:60%;overflow:auto;font-size:1.2rem ;}
                  #{$id}{$bt}{background-color:#EAC7EB;padding:3% 15%;border-radius:0.9vh;background-color:{$color};}
                        ";
      $mobile[]="
            @media(max-width:920px){
                  .cmdl{width:90%;height:69%;
                        }";
    if(is_array($array)){
      for($v=0;$v<$length-1;$v++){
            $item = "<a id='{$id}{$v}' href={$array[$v]['href']}>{$array[$v]['content']}</a>";
                  if(array_key_exists('lnk', $array[$v])){$item .= "<a id='{$id}i{$v}' href='{$array[$v]['lnk']}' target='blank'><i id='i'class='fa-solid fa-arrow-up-right-from-square' aria-hidden='true'></i></a>";  }
            $linha .= "<span id='itm'>{$item}</span>";  }
}else{$linha = $array;}
      $imdl .= "<div class='content'>{$linha}</div>";
      if(is_array($array)){ $imdl .= "<a class='mbt' href='/' id='{$id}{$bt}'>{$array[($length-1)]['content']}</a>";}
        else{$imdl .= "<a class='closeX' href='#'>X</a>";}
        $mdl[] = "<div class='cmdl'>{$imdl}</div>";
            return "<div id='{$id}' class='mdl'>".implode('', $mdl)."</div>";  

}

function search($tm,$lnksData){
      global $style,$script,$fscript,$mobile,$lupa;$campo='';$btp='';
      $names = array_column($fscript, 'name');
if (!in_array("search", $names)){$script[]=$fscript[2]['code'];}
    $jsonData = json_encode($lnksData);
    $script[]= 'const jsonData='.$jsonData.'; const data =JSON.parse(JSON.stringify(jsonData));';

      $style[]= "
                  #search{width: calc({$tm}% + 18%);height:calc({$tm}% + 3%);min-width:44px;min-height:44px;position:relative;display:flex;border:solid 0.06vh black;border-radius: 0.9vh;display: flex;justify-content: space-between;flex-shrink: 1;background-color: white;}
                  #search input{width: 60%;height:100%;font-size:100%;border-radius: 0.9vh;margin-left:3%;}

                  #search button{min-width:44px;width: 30%;min-height:100%;border-radius: 0.9vh;display: flex;align-items: center;font-size:100%;font-weight:bold;cursor: pointer;z-index:20;}

                  #search a{min-width:88px;width: 39%;height:100%;position:absolute;right:0;z-index:1;flex-grow:1;} #search a:hover{background:rgba(90,90,90,0.09);} #search svg{width:45%;height:51%;overflow:visible;stroke:#787878;}";
    $mobile[]="
            #search{width:60%;min-height:44px;}
            #search input{width:57%;}
            #search button{width:24%;}
        ";

      $campo= '<input aria-label="barra de pesquisa" id="busca" placeholder="Pesquise..." type="text">';

      return "<span id='search'>{$campo}<a aria-label='resetar pesquisa' href='?'></a><button aria-label='Buscar' id='busque' onclick='pesquisar(data)'>".$lupa."</button></span>";
}

function textblock($texto,$bg,$tm){
      global $style;
      $style[]= ".textblock{width:100%;height:auto;font-size:90%;overflow:visible;padding:1.8%;text-align:justify;display:flex;justify-content:center;border-radius:calc({$tm}*0.03vw);display:flex;background:{$bg};}
                 .textblock p{width:84%;height:auto;overflow:visible;text-align:justify;font-size:90%;}";
      return "<div class='textblock'><p>{$texto}</p></div> ";
}

function slideshow($id,$array,$tm,$auto){
      global $style,$script,$fscript,$mobile;
      $names = array_column($script, 'name');
if (!in_array("slideshow", $names)){$script[]=$fscript[0]['code'];}
      $items=[];
      $length = count($array);
      $style[] = ".slideshow, #d{$id}{width:calc({$tm}vw*2);height:calc({$tm}vw*1);position:relative;border-radius:calc({$tm}*0.06vw);border:solid 0.03vh black;overflow:hidden;}
                  .items_slideshow{width:100%;height:100%;display:flex;justify-content:flex-start;overflow:auto;scrollbar-width:none;scroll-snap-type:x mandatory;transition: transform 0.9s ease;}.slideshow::-webkit-scrollbar{display:none;}
                  .item{min-width:100%;height:100%;scroll-snap-align:center;cursor:default;}.item:hover{opacity:1;}
                  .slideshow #bt{width:calc({$tm}vw*2 / 6);height:100%;right:0;top:0;position:absolute;z-index:10;font-size:calc({$tm}vw*1/3);text-align:center;line-height:calc({$tm}vw*1);opacity:0;background-color:rgba(0,0,0,0.06);}.slideshow #bt:hover{opacity:0.3; transition:0.9s;cursor:pointer;}
                  ";
      $mobile[]="
            @media(max-width:920px){                  
                  #d{$id}{width:calc({$tm}vw*6);height:calc({$tm}vw*3);}
                  }";
      for($i=0;$i<$length;$i++){
            $items[] = "<a href='{$array[$i]['url']}' id='{$id}{$i}' class='item' target='blank' title='isto é um componente slideshow.'></a>";
            $style[] ="#{$id}{$i}{background:url({$array[$i]['image']}) no-repeat;background-size:contain;background-position:center;}";}
 
      $script[] = "
      function slideshow{$id}(){
      let item = document.querySelector('#{$id}').offsetWidth;
      let last = {$length};
      let id = '{$id}';
      slideshow(item,last,id);
      }";
      if($auto !== 0){$script[]= "setInterval(slideshow{$id}, {$auto} *1000);";}

      return "<div class='slideshow' id='d{$id}'><div id='{$id}' class='items_slideshow'>".implode('', $items)."</div><button id='bt' onclick='slideshow{$id}()'> > </button></div>";
}

function hamburguer($mode,$array,$tm){
      global $style,$script,$fscript;
      $names = array_column($script, 'name');
    if (!in_array("hamburguer", $names)){$script[]=$fscript[1]['code'];}
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

//Cria um menu tab.
function tabs($array,$bg) {//$array recebe um array associativo com as seguintes chaves(title / content).$bg recebe a cor de fundo do menu tabs.
    $tabs = [];$content = [];$check=[];
    global$style;
    $length = count($array);

    for ($i = 0; $i < $length; $i++) {
        $tabs[] = "<input class='tabsBt' name='tabs' type='radio' id='tab{$i}' checked><label for='tab{$i}'>{$array[$i]['title']}</label>";
        $content[]= "<div class='content' id='cnt{$i}'>{$array[$i]['content']}</div>";
        $check[]= "input#tab{$i}:checked ~ #cnt{$i},";}

            $style[] = "
                  .tabs{width:100%;height:fit-content;display:grid;grid-template-columns:repeat({$length}, 1fr);grid-template-rows:auto 2fr;border-radius:0.6vh 0.6vh 0 0;}
                  .tabs label{width:100%;grid-row:1;padding:0.9vw;font-size:1.5rem;font-weight:bold;background:$bg;text-align:center;cursor:pointer;}
                  input[type='radio']:checked + label,label:hover{opacity:0.72;}
                  .tabs .tabsBt{display:none;}
                  .tabs .content{width:100%;height:fit-content;visibility:hidden;padding:2.1vh;grid-column:1/-1;grid-row:2;border-top:solid 0.12vh $bg;cursor:text;border:solid 1px $bg;text-align:justify;overflow:auto;}".
                  implode('', $check)."p{visibility:visible;height:auto;}
                        ";
            $mobile[]="
            @media(max-width:920px){
                  .tabs .content{font-size:;}
            }
                        ";

      
    return "<div class='tabs'>".implode('', $tabs).implode('', $content). "</div>";
}
function tagList($id,$array,$tm){
      global$style,$mobile;
      $tags=[];$length=count($array);

      $style[]= "
            .tagList{width:auto;}
            .{$id}{width:calc({$tm}vw*7.8);height:calc({$tm}vw*1.5);display:flex;justify-content:start;padding:1.2% 0;margin:1.5% 0;border-top:solid 0.12vh black;padding:calc({$tm}vw/6) 0.18%;overflow:hidden;}
            .{$id} .img{width:calc({$tm}vw*2.4);height:100%;overflow:hidden;background:cyan; background-repeat:no-repeat;background-size:contain;background-position:center;transition: background 0.9s ease;}
            textarea{width:81%;text-align:justify;padding:0 1.2%;font-size:0.7rem;resize:none;background:none;overflow:hidden;}
                  ";
      $mobile[]="
      @media(max-width:920px){
            .tag{width:calc({$tm}vw*22.1);height:calc({$tm}vh*6);padding:1.2% 0;margin:0;}
            .tag .img{width:calc({$tm}vw*3);height:calc({$tm}vw*1.5);background:cyan; background-repeat:no-repeat;background-position:center;transition: background 0.9s ease;}
            .tag textarea{min-height:calc({$tm}vh*5.1);font-size:0.8rem;}
      }
                  ";
      for($i=0;$i<$length;$i++){
          $tag[]= "<a href={$array[$i]['url']} aria-label='link clicável'><div class='tag {$id}' aria-label='IMAGEM DE EXEMPLO'><div class='img' data-bg='url({$array[$i]['image']})' loading='lazy'></div></a><textarea row='12' aria-label={$array[$i]['text']} disabled>{$array[$i]['text']}</textarea></div>";
      }

      return "<div class='tagList' id='{$id}'>".implode('',$tag)."</div>";
}

function arquivoBd($tbl,$cln,$ref,$con){
    $arq=[];
    $servername = $con[0];
    $username = $con[1];
    $password = $con[2];
    $dbname = $con[3];

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // 2. Especificar a consulta SQL para buscar o arquivo (por exemplo, por nome)
    $nome_arquivo = $ref; // O nome do arquivo que você quer buscar
    $sql = "SELECT * FROM {$tbl} WHERE {$cln} = ?";

    // 3. Preparar a consulta e vincular o parâmetro
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome_arquivo); // "s" indica que o parâmetro é uma string

    // 4. Executar a consulta
    $stmt->execute();

    // 5. Obter o resultado
    $result = $stmt->get_result();

    // 6. Verificar se o arquivo foi encontrado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Arquivo não encontrado.";
    }

    // 7. Fechar a conexão
    $stmt->close();
    $conn->close();
return $row;
    
}

function section($id,$section){
global $main;
      return "<section class={$id}>".implode('',$section).'</section>';

}
function grid($id,$grids,$title){
    global $main, $style,$script,$mobile,$lstyle;$section=[];    $tm=count($grids);
        $style[]="
            #{$id} {width:81vw;display:flex;flex-flow:row wrap;align-items:center;justify-content:space-around;padding:0.9rem 0;margin:1.5rem;border-radius:0.6rem;box-shadow: 0px 0px 10px rgba(90, 90, 90, 0.3);}
            #{$id} h1{width:100%;text-align:center;font-size:2.4rem;text-decoration:underline;grid-column:1;}
            #{$id} div{width:27rem;height:30rem;margin-top:0.9rem;box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);display:flex;flex-direction:column;padding:0.6rem 1.2rem;justify-content:space-between;border-radius:0.9rem;}
            #{$id} figure{width:100%;height:100%;}
            #{$id} p{display:none;}
                ";
    $style[]=" @media(max-width:990px) and (orientation:landscape){
            #{$id} {overflow-y:visible;overflow-x:scroll;width:93vw;min-height:36rem;padding:0 1.5rem;box-sizing:;display:grid;flex-wrap:nowrap;justify-content:flex-start;justify-items:start;gap:1.5rem;grid-template-row:1fr 1fr;grid-template-column:12%;scroll-snap-type:x mandatory;transition:transform 3s easy-out;scroll-behavior:smooth;}
            #{$id} h1{position:sticky;top: 0;left:0;z-index:3;grid-row:1;}
            #{$id} div{width:81vw;grid-row:2;scroll-snap-align:center;}
                }
                ";
        $mobile[]="
            #{$id} {overflow-x:scroll;width:99vw;min-height:84vh;padding:0 4.2vw;box-sizing:border-box;display:flex;flex-flow:row nowrap;justify-content:flex-start;items-align:start;gap:3rem;scroll-snap-type:x mandatory;transition:transform 3s easy-out;scroll-behavior:smooth;}
            #{$id} h1{display:none;visibility:hidden;}
            #{$id} div{min-width:90vw;height:72vh;scroll-snap-align:center;}
                ";
        for($i=0;$i<6;$i++){
            
            $section[] = "<div><figure id='img{$id}{$i}' ></figure><h2><button class='vr'>ver mais...</button></h2><p></p></div>";
            }
        
        return "<section id={$id}><h1>{$title}</h1>".implode($section)."</section>";
}
function formwhats($id, $phone, $bg) {
    global $script, $style, $fscript;
    
    $names = array_column($script, 'name');
    if (!in_array("sendwhats", $names)) {
        $script[] = $fscript[3]['code'];
    }

    // Escapar corretamente os atributos
    $idEsc = htmlspecialchars($id, ENT_QUOTES);
    $phoneEsc = htmlspecialchars($phone, ENT_QUOTES);
    $bgEsc = htmlspecialchars($bg, ENT_QUOTES);

    $form = "<form id=\"{$idEsc}\" onsubmit=\"sendwhats('{$idEsc}Nm','{$idEsc}Mm','{$phoneEsc}'); return false;\">
        <input id=\"{$idEsc}Nm\" type=\"text\" placeholder=\"Seu nome\">
        <textarea id=\"{$idEsc}Mm\" placeholder=\"Sua mensagem\"></textarea>
        <input id=\"{$idEsc}St\" value=\"ENVIAR\" type=\"submit\">
    </form>";

    $style[] = "
        form#{$idEsc} {width: 81%;display: flex;flex-flow: column nowrap;gap: 0.9rem;}
        form#{$idEsc} input,
        form#{$idEsc} textarea {width: 90%;height: 3rem;padding: 0.9rem;font-size: 1.14rem;border-radius: 0.3rem;box-shadow: 0 0 0.1rem 0.1rem rgba(78,78,78,0.09);}
        form#{$idEsc} textarea {height: 9rem;resize: none;}
        form#{$idEsc} input[type=submit] {background: {$bgEsc};color: #FFFFFF;font-weight: bold;}
    ";

    return $form;
}


function aesEncrypt($base64, $key) {
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = random_bytes($iv_length); // Gerar um vetor de inicialização aleatório

    // Criptografar a string Base64
    $encrypted = openssl_encrypt($base64, 'aes-256-cbc', $chave, 0, $iv);

    // Codificar em Base64 para transporte/armazenamento
    return base64_encode($iv . $encrypted);
}

function aesDecrypt($base64, $key) {
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');

    // Decodificar os dados
    $data = base64_decode($base64);
    $iv = substr($data, 0, $iv_length); // Extrair o IV
    $encrypted_data = substr($data, $iv_length); // Extrair os dados criptografados

    // Descriptografar
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $chave, 0, $iv);
}

function cachePage($title, $content, $mode, $update) {
    $arquivo = 'cache/' . $title . '.html';
    
    // Verifica se o arquivo existe
    if (file_exists($arquivo)) {
        // Obtém a data de criação do arquivo
        if($update=true){$fileCreationTime = '50000';}
        else{$fileCreationTime = filemtime($arquivo);}
        
        // Verifica se o arquivo ainda está dentro do tempo de expiração
        if (time() - $fileCreationTime < '43200') {
            // Serve o conteúdo do arquivo HTML
            header("Content-Type: text/html; charset=UTF-8");
            readfile($arquivo);
            exit();  // Encerra o script após servir o arquivo cacheado
        } else {
            // Se o arquivo expirou, remove o arquivo de cache
            unlink($arquivo);
            echo $content;
        }
    }
    else{
        // Se o arquivo não existir ou tiver expirado, cria o novo arquivo de cache
        if ($mode == 'create') {
            file_put_contents($arquivo, $content);
            echo $content;  // Exibe o conteúdo criado
            return;
        }

        if ($mode == 'return') {
            return;  // Retorna sem fazer nada
        }
    }

    return;
}

function getJsonData($url, $parametro, $authToken, $pageToken = null) {
    if (!is_array($parametro) || count($parametro) !== 2) {
        return null;
    }

    list($tabela, $idOuTipo) = $parametro;
    $identKey = is_numeric($idOuTipo) ? 'id' : 'tipo';

    $query = http_build_query([
        'tbl' => $tabela,
        $identKey => $idOuTipo
    ]);

    $url = $url . '?' . $query;

    $origin = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

    $headers = [
        'Authorization: ' . $authToken,
        'Origin: ' . $origin,
        'Accept: application/json'
    ];

    if ($pageToken) {
        $headers[] = 'X-Page-Token: ' . $pageToken;
    }

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 20
    ]);

    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headersRaw = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    curl_close($ch);

    preg_match('/Content-Type:\s*(.*)/i', $headersRaw, $matches);
    $contentType = isset($matches[1]) ? trim($matches[1]) : '';

    // Se for binário (imagem, vídeo, áudio, aplicação)
    if (preg_match('#^(image|video|audio|application)/#', $contentType)) {
        $tipo = explode('/', $contentType)[0];
        $ext = explode('/', $contentType)[1] ?? 'bin';
        $pasta = $_SERVER['DOCUMENT_ROOT']."/assets/{$tipo}s";

        if (!file_exists($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $fileName = $tipo . '_' . time() . '.' . $ext;
        $filePath = $pasta."/{$fileName}";

        file_put_contents($filePath, $body);

        return 'https://'.$_SERVER['HTTP_HOST']."/{$tipo}s/{$fileName}";
    }

    // Se for JSON, retorna array decodificado
    $json = json_decode($body, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $json;
    }

    // 🟨 Se não for binário nem JSON, retorna como string pura
    return $body;
}







function normalize(string $string) {
    // Normaliza acentos (converte para caracteres sem acento)

    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    // Remove qualquer caractere que não seja uma letra, número, hífen, underscore ou vírgula
    $string = preg_replace('/[^a-zA-Z0-9-_]/', '', $string);
    // Remove espaços
    $string = str_replace(' ', '', $string);
    return $string;
}

function filter_image($caminho_arquivo) {
    // Verifica se o arquivo existe
    if (!file_exists($caminho_arquivo)) {
        return false;
    }

    // Obtém a extensão do arquivo
    $extensao = strtolower(pathinfo($caminho_arquivo, PATHINFO_EXTENSION));
    $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'];

    // Verifica se o arquivo é uma imagem válida usando getimagesize()
    $info_imagem = getimagesize($caminho_arquivo);

    // Verifica se a função getimagesize() retornou um valor válido e se o MIME é aceito
    if ($info_imagem === false) {
        return false; // Não é uma imagem válida
    }

    // Verifica o MIME da imagem
    $mime_valido = in_array($info_imagem['mime'], ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff', 'image/webp']);

    // Retorna true se todas as condições forem verdadeiras
    return in_array($extensao, $extensoes_validas) && $mime_valido;
}


/*  Imagens "SVG"   */
$lupa='
<svg width="100%" height="100%" class="princess" style="background:;" xmlns="http://www.w3.org/2000/svg">
	<g>
		<circle cx="42%" cy="42%" r="33%" fill="none" stroke-width="16.81%" />
		<line x1="69%" y1="69%" x2="89.15%" y2="89.15%" stroke-width="21%" stroke-linecap="round" />
	</g>
</svg>';
$envelope="
<svg class='envelope' width='300px' height='180px' viewBox='0 0 300 180' stroke-height='6em'stroke-width='3em' style='color:blue;'>
    <rect width='100%' height='90%' rx='3%' ry='3%' fill='none' />
    <polyline points='0 30 150 90 300 30' fill='none' stroke-width='1.8em' />
  </svg>";
