<?php
function tagList($id, $array, $tm) {
    global $styleVar, $style, $media_mobile_portrait_geral, $media_mobile_landscape_geral, $media_desktop_landscape_geral, $media_tablet_portrait_geral, $media_tablet_landscape_geral;
    $tag = [];
    $length = count($array);

    // Estilos 100% dinâmicos
    if(!in_array("--tagList-tm:$tm;", $styleVar)){
        $styleVar[] = "--tagList-tm:$tm;";
        }
    $style[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tagList.css");
    
    $media_desktop_landscape_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/desktopL/tagList.css");

    $media_mobile_landscape_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/mobileL/tagList.css");
    $media_mobile_portrait_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/mobileP/tagList.css");
    
    $media_tablet_portrait_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tabletP/tagList.css");
    $media_tablet_landscape_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tabletL/tagList.css");
    
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