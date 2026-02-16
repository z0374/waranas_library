<?php
function textblock($texto, $bg, $tm) {
    global $styleVar, $style, $media_mobileP, $media_mobileL, $media_desktopP, $media_desktopL;

    $styleVar[] = "
        --textBlock-tm:{$tm};
        --textBlock-bg:{$bg};
        ";
    $style[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/textBlock.css");
    $media_desktopP[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/desktopP/textBlock.css");
    $media_desktopL[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/desktopL/textBlock.css");
    $media_mobileP[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/mobileP/textBlock.css");
    $media_mobileL[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/mobileL/textBlock.css");
    
    return "<div class='textblock'><p>{$texto}</p></div>";
}