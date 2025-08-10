<?php
function btFloat($name, $lnk) {
    global $body, $style, $css_files;

    $component_css = 'assets/css/components/btFloat.css';
    if (!in_array($component_css, $css_files)) {
        $css_files[] = $component_css;
    }

    $body[] = '<a class="btFloat" href=' . $lnk . ' style="position:absolute;top:0.3vw;left:0.6vw;">' . $name . '</i></a>';
    // O estilo foi movido para o arquivo CSS. A linha $style[] foi removida.
}