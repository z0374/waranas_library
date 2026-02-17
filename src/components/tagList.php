<?php
function tagList($id, $array, $tm) {
    global $styleVar, $style, $media_mobileP, $media_mobileL, $media_desktopL;
    $tag = [];
    $length = count($array);

    // Estilos 100% dinâmicos
    if(!in_array("--tagList-tm:$tm;", $styleVar)){
        $styleVar[] = "--tagList-tm:$tm;";
        }
    $style[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tagList.css");
    
    $media_desktopL[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/desktopL/tagList.css");
    $media_mobileL[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/mobileL/tagList.css");
    $media_mobileP[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/mobileP/tagList.css");
    
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