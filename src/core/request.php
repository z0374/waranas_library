<?php
function getJsonData($url, $parametro, $authToken, $pageToken = null) {
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
        $error = curl_error($ch);
        curl_close($ch);
        return [];
    }
    
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headersRaw = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    curl_close($ch);

    // O resto da lógica para salvar arquivos ou decodificar JSON...
    $json = json_decode(json: $body, associative: true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $json;
    }

    return $body; // Retorna o corpo como string se não for JSON.
}