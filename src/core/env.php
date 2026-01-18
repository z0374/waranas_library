<?php
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Acesso direto proibido.");
}

function loadEnv(string $filePath): void {
    if (!file_exists($filePath)) {
        throw new Exception("Arquivo .env não encontrado em: {$filePath}");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        // Ignora comentários e linhas vazias
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        // Divide em chave=valor com segurança
        $parts = explode('=', $line, 2);
        if (count($parts) < 2) continue; // ignora linhas inválidas

        $name = trim($parts[0]);
        $value = trim($parts[1]);

        // Remove aspas externas
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }

        // Define variável de ambiente
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}

function unsetEnv(string $filePath): callable {
    if (!file_exists($filePath)) {
        throw new Exception("Arquivo .env não encontrado em: {$filePath}");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $loadedVars = [];

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        $parts = explode('=', $line, 2);
        if (count($parts) < 2) continue;

        $name = trim($parts[0]);
        $value = trim($parts[1]);

        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }

        putenv("$name=$value");
        $_ENV[$name] = $value;
        $loadedVars[] = $name;
    }

    return function() use ($loadedVars) {
        foreach ($loadedVars as $var) {
            unset($_ENV[$var]);
            putenv($var);
        }
    };
}