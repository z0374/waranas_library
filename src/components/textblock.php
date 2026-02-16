<?php
function textblock($texto, $bg, $tm) {
    global $styleVar, $style, $media_mobileP, $media_mobileL, $media_desktopP, $media_desktopL;

    $styleVar[] = "
        --textBlock-tm:{$tm};
        --textBlock-bg:{$bg};
        ";
    $style[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/textBlock.css");
    
    return "<div class='textblock'><p>{$texto}</p></div>";
}