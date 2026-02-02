<?php
function filter_image($caminho_arquivo) {
    if (!file_exists($caminho_arquivo) || !is_readable($caminho_arquivo)) {
        return false;
    }

    $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extensao = strtolower(pathinfo($caminho_arquivo, PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoes_validas)) {
        return false;
    }

    $info_imagem = getimagesize($caminho_arquivo);
    if ($info_imagem === false) {
        return false;
    }

    $mime_valido = in_array($info_imagem['mime'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    return $mime_valido;
}

function isSvg($filePath) {
    // 1. Verifica se o arquivo existe
    if (!file_exists($filePath)) {
        return false;
    }

    // 2. Verificação básica de Mime Type
    // Nota: SVGs as vezes são detectados como 'text/plain' ou 'text/xml' dependendo do servidor
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $filePath);
    finfo_close($finfo);

    $allowedMimes = ['image/svg+xml', 'text/plain', 'text/xml', 'application/xml'];
    
    if (!in_array($mimeType, $allowedMimes)) {
        return false;
    }

    // 3. Verificação Estrutural (Tentando ler como XML)
    // Suprime erros de XML mal formatado para não sujar o log
    libxml_use_internal_errors(true);
    
    $dom = new DOMDocument();
    // Tenta carregar o arquivo. Se falhar, não é um XML válido.
    $loaded = $dom->load($filePath);
    
    // Limpa os erros do buffer do libxml
    libxml_clear_errors();

    if (!$loaded) {
        return false;
    }

    // 4. Verifica se a tag raiz é realmente <svg>
    // Converte para minúsculo para garantir compatibilidade
    if (strtolower($dom->documentElement->tagName) !== 'svg') {
        return false;
    }

    return true;
}