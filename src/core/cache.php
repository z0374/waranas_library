<?php

function cachePage($title, $content = null, $mode = 'create', $force_update = false)
{
    // 1. Criar uma chave única baseada no título e na URL da requisição
    // Isso garante que ?url=siteA tenha um cache diferente de ?url=siteB
    $urlParam = isset($_GET['url']) ? $_GET['url'] : 'setup';
    $cacheKey = md5($title . $urlParam);
    
    $arquivo = ROOT_PATH_WARANAS_LIB . '/cache/' . $cacheKey . '.html';
    $validade = 43200; // 12 horas

    // 2. TENTATIVA DE ENTREGA IMEDIATA
    if (!$force_update && file_exists($arquivo)) {
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

    // 3. SE FOR CREATE E O CONTEÚDO JÁ VEIO PRONTO
    if ($mode === 'create' && $content !== null) {
        $cacheDir = ROOT_PATH_WARANAS_LIB . '/cache';

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        
        file_put_contents($arquivo, $content, LOCK_EX);
        header("Content-Type: text/html; charset=UTF-8");
        header("X-Cache: MISS-STORED");
        echo $content;
        exit;
    }
}