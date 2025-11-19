<?php
function getJsonData($url, $parametro, $authToken, $pageToken = null) {
    // DIR_BASE deve estar definido no escopo global (ex: no index.php)
    global $DIR_BASE;

    if (!is_array($parametro) || count($parametro) !== 2) {
        return ['error' => 'Parâmetro inválido'];
    }

    list($tabela, $idOuTipo) = $parametro;
    $identKey = is_numeric($idOuTipo) ? 'id' : 'tipo';

    $query = http_build_query(['tbl' => $tabela, $identKey => $idOuTipo]);
    $full_url = $url . '?' . $query;

    $origin = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

    $headers = [
        'Authorization: ' . $authToken,
        'Origin: ' . $origin,
        'Accept: application/json'
    ];
    if ($pageToken) {
        $headers[] = 'X-Page-Token: ' . $pageToken;
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $full_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 20
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($response === false) {
        // Erro de cURL (ex: timeout, conexão)
        $error = curl_error($ch);
        curl_close($ch);
        return [];
    }
    
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headersRaw = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    curl_close($ch);

    // -----------------------------------------------------
    // 1. Lógica de Salvamento de Imagem (Conteúdo Binário)
    // -----------------------------------------------------

    if (preg_match('/Content-Disposition:.*?filename="(.*?)"/i', $headersRaw, $matches)) {
        $fileName = $matches[1];
        
        // Define o caminho completo para salvar o arquivo
        $imagePath = ($DIR_BASE ?? __DIR__) . '/assets/images/';
        $targetPath = $imagePath . $fileName;

        // Garante que o diretório de imagens exista
        if (!is_dir($imagePath)) {
            mkdir($imagePath, 0755, true);
        }
        
        // Verifica o Content-Type para confirmar que é um tipo de arquivo válido (imagem/pdf/etc)
        if (preg_match('/Content-Type: (image\/\w+)/i', $headersRaw, $typeMatches)) {
            // Salva o corpo binário no disco
            if (file_put_contents($targetPath, $body) !== false) {
                // Retorna o nome do arquivo, que será usado para construir a URL no frontend
                return $fileName; 
            }
        }
    }

    // -----------------------------------------------------
    // 2. Lógica de Decodificação (Conteúdo JSON ou Texto)
    // -----------------------------------------------------
    $json = json_decode(json: $body, associative: true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $json; // Retorna o array PHP
    }

    return $body; // Retorna o corpo como string (útil para listas de IDs, ou erro do D1).
}