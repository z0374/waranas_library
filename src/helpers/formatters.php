<?php
function BRL($valor) {
    $valor = floatval(preg_replace('/[^\d,.]/', '', $valor));
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function cepFormat($cep) {
    // Remove todos os caracteres não numéricos
    $numericCEP = preg_replace('/\D/', '', $cep);

    // Verifica se o CEP tem exatamente 8 dígitos
    if (strlen($numericCEP) !== 8) {
        throw new Exception('CEP inválido. Deve conter exatamente 8 dígitos.');
    }

    // Formata o CEP no padrão 00000-000
    $formattedCEP = substr($numericCEP, 0, 5) . '-' . substr($numericCEP, 5, 3);

    return $formattedCEP;
}