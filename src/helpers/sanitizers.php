<?php
function normalize(string $string): string {
    $string = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $string); // Remove caracteres não-letras/números
    $string = str_replace(' ', '-', $string); // Substitui espaços por hífens
    $string = strtolower($string);
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string); // Remove acentos
    $string = preg_replace('/[^a-z0-9-]/', '', $string); // Limpeza final
    return trim($string, '-');
}