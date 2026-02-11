<?php
function cachePage($title, $content = null, $mode = 'create', $force_update = false)
{
    $arquivo = ROOT_PATH_WARANAS_LIB . '/cache/' . md5($title) . '.html';
    $validade = 43200; // 12 horas

    // 1. TENTATIVA DE ENTREGA IMEDIATA
    if (!$force_update && file_exists($arquivo)) {
        // Se for modo return e expirou, sai para processar
        if ($mode === 'return' && (time() - filemtime($arquivo) > $validade)) {
            return;
        }
        header("Content-Type: text/html; charset=UTF-8");
        header("X-Cache: HIT");
        readfile($arquivo);
        exit;
    }

    if ($mode === 'return')
        return;

    // 2. SE FOR CREATE E O CONTEÚDO JÁ VEIO PRONTO (Seu caso na função html)
    if ($mode === 'create' && $content !== null) {
        $cacheDir = ROOT_PATH_WARANAS_LIB . '/cache';

        // Verifica se a pasta existe, se não, cria com permissões de leitura/escrita
        if (!is_dir($cacheDir)) {
            // 0755 é o padrão, true permite criar pastas aninhadas
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($arquivo, $content, LOCK_EX);
        header("Content-Type: text/html; charset=UTF-8");
        header("X-Cache: MISS-STORED");
        echo $content;
        exit;
    }
}