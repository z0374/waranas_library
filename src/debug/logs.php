<?php

/**
 * Função para registrar logs em arquivo.
 * * @param mixed  $text  O conteúdo do log (pode ser string, array ou objeto).
 * @param string $type  O tipo do log (ex: ERROR, INFO, DEBUG, WARNING). Padrão: INFO.
 */
function logs($text, $type = 'INFO') {
    // 1. Define o fuso horário para garantir a hora correta (ajuste conforme sua região)
    date_default_timezone_set('America/Sao_Paulo');

    // 2. Define o caminho da pasta e do arquivo
    // __DIR__ refere-se ao diretório onde este arquivo está. Ajuste o '/../../logs' conforme sua estrutura.
    // Neste exemplo, ele tentará criar uma pasta 'logs' dois níveis acima ou na raiz.
    $logDir = ROOT_PATH_WARANAS_LIB . '/logs'; 
    
    // Opção: Criar um arquivo por dia para não ficar gigante (ex: 2023-10-27.log)
    $filename = $type . "-" . date('Y-m-d') . '.log';
    $logFile = $logDir . '/' . $filename;

    // 3. Verifica se o diretório existe. Se não, cria com permissões de escrita.
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // 4. Tratamento de dados: Se $text for array ou objeto, converte para JSON para ser legível
    if (is_array($text) || is_object($text)) {
        $text = json_encode($text, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    // 5. Formata a linha do log
    // Formato: [DATA HORA] [TIPO] MENSAGEM
    $date = date('Y-m-d H:i:s');
    $logMessage = "[$date] [" . strtoupper($type) . "] $text" . PHP_EOL;

    // 6. Escreve no arquivo
    // FILE_APPEND: Adiciona ao final do arquivo em vez de sobrescrever.
    // LOCK_EX: Bloqueia o arquivo durante a escrita para evitar conflitos de processos simultâneos.
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
}