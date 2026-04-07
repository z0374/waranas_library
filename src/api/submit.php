<?php

/**
 * Envia dados via método POST em formato JSON para uma API externa (Cloudflare)
 *
 * @param string $endpoint  O caminho do Worker (ex: '/api/salvar-dados')
 * @param array|object $data  Os dados que serão convertidos para JSON e enviados
 * @param string $configUrl O domínio base configurado no seu .env
 * @param string $authToken O token de autenticação configurado no seu .env
 * @return array|null       Retorna o JSON decodificado da resposta ou null em caso de erro
 */
function postJsonData($endpoint, $data, $configUrl, $authToken) {
    // Garante que a URL comece com https://
    $url = "https://" . rtrim($configUrl, '/') . '/' . ltrim($endpoint, '/');
    
    // Codifica o array do PHP em uma string JSON
    $jsonData = json_encode($data);

    // Inicializa a sessão cURL
    $ch = curl_init($url);

    // Configura as opções do cURL para POST
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna a resposta como string
    curl_setopt($ch, CURLOPT_POST, true);           // Define o método como POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);// Anexa o corpo da requisição

    // Define os cabeçalhos essenciais
    // A chave de Auth pode ser passada como um Bearer Token ou um header customizado,
    // dependendo de como o seu Cloudflare Worker espera validar a variável `config_auth`
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData),
        'Authorization: Bearer ' . $authToken, 
        // ou, se preferir usar um header customizado: 'X-Auth-Token: ' . $authToken
    ]);

    // Executa a requisição e captura a resposta e o código HTTP
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_errno($ch);

    // Fecha a conexão cURL
    curl_close($ch);

    // Tratamento básico de erros
    if ($curlError) {
        // Você pode implementar um log de erro aqui no futuro
        return false; 
    }

    // Verifica se a API retornou status de sucesso (200 - 299)
    if ($httpCode >= 200 && $httpCode < 300) {
        // Retorna a resposta da Cloudflare como um Array do PHP
        return json_decode($response, true);
    }

    // Retorna false se o HTTP Code for de erro (ex: 403, 404, 500)
    return false;
}