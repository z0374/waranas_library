<?php

function colorTone($hex, $percent, $mode = 'darken' || 'lighten') {
    // Remove o símbolo # se presente
    $hex = ltrim($hex, '#');

    // Expande formatos curtos (#abc → #aabbcc)
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0] . $hex[1].$hex[1] . $hex[2].$hex[2];
    }

    // Converte para RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Ajusta cor com base no modo
    switch (strtolower($mode)) {
        case 'lighten':
            $r = min(255, $r + ($r * $percent / 100));
            $g = min(255, $g + ($g * $percent / 100));
            $b = min(255, $b + ($b * $percent / 100));
            break;

        case 'darken':
        default:
            $r = max(0, $r - ($r * $percent / 100));
            $g = max(0, $g - ($g * $percent / 100));
            $b = max(0, $b - ($b * $percent / 100));
            break;
    }

    // Converte de volta para HEX
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}