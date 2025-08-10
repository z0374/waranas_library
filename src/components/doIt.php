<?php
/**
 * Função construtora genérica para HTML.
 */
function doIt($option, $adition, $property = '', $content = '') {
    // Opção 'A': Retorna uma tag HTML completa com os parâmetros necessários.
    if ($option == 'A') {
        return "<{$adition} {$property}>{$content}</{$adition}>";
    }
    // Opção 'B': Retorna conteúdo livre (útil para injetar strings em outros arrays).
    if ($option == 'B') {
        return "{$adition}";
    }
    return ''; // Retorno padrão
}