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