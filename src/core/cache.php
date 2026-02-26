<?php

function cachePage($title, $content = null, $mode = 'create', $force_update = false)
{
    $urlParam = isset($_GET['url']) ? $_GET['url'] : 'setup';
    $cacheKey = md5($title . $urlParam);
    
    $arquivo = ROOT_PATH_WARANAS_LIB . '/cache/' . $cacheKey . '.html';
    $validade = 43200; // 12 horas

    // MODO VERIFY: Lê o cache e tenta preencher lacunas de tempo antes de entregar
    if ($mode === 'verify') {
        if (file_exists($arquivo) && (time() - filemtime($arquivo) < $validade)) {
            $cachedHtml = file_get_contents($arquivo);
            
            // Chama a nova função de processamento incremental
            $updatedHtml = processPending($cachedHtml);

            // Se houve atualização (preencheu lacunas), salva o novo cache
            if ($updatedHtml !== $cachedHtml) {
                file_put_contents($arquivo, $updatedHtml, LOCK_EX);
                header("X-Cache-Status: INCREMENTAL-UPDATE");
                return $updatedHtml;
            }
            
            return $cachedHtml;
        }
        return null;
    }

    // TENTATIVA DE ENTREGA IMEDIATA (HIT)
    if (!$force_update && file_exists($arquivo)) {
        if ($mode === 'return' && (time() - filemtime($arquivo) > $validade)) return;
        
        header("Content-Type: text/html; charset=UTF-8");
        header("X-Cache: HIT");
        readfile($arquivo);
        exit;
    }

    if ($mode === 'return') return;

    // CRIAR CACHE (MISS)
    if ($mode === 'create' && $content !== null) {
        $cacheDir = ROOT_PATH_WARANAS_LIB . '/cache';
        if (!is_dir($cacheDir)) mkdir($cacheDir, 0755, true);
        
        file_put_contents($arquivo, $content, LOCK_EX);
        header("Content-Type: text/html; charset=UTF-8");
        header("X-Cache: " . ($force_update ? "OVERWRITTEN" : "STORED"));
        echo $content;
        exit;
    }
}
