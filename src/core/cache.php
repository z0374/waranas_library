<?php
function cachePage($title, $content, $mode = 'create', $force_update = false) {
    $cacheDir = __DIR__ . '/../../cache'; // Aponta para o diretório cache na raiz
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    $arquivo = $cacheDir . '/' . $title . '.html';
    $tempoDeExpiracao = 43200; // 12 horas em segundos

    if (file_exists($arquivo) && !$force_update) {
        $fileCreationTime = filemtime($arquivo);
        if ((time() - $fileCreationTime) < $tempoDeExpiracao) {
            header("Content-Type: text/html; charset=UTF-8");
            readfile($arquivo);
            exit();
        }
    }

    // Se o cache não existe, expirou, ou a atualização foi forçada, (re)cria o arquivo.
    if ($mode == 'create') {
        file_put_contents($arquivo, $content);
        // Exibe o conteúdo recém-criado.
        echo $content;
    }
}