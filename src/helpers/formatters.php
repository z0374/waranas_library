<?php
/**
 * Transforma um valor numérico ou string em formato de moeda BRL (Real).
 * Garante que 44 se torne R$ 44,00 e 44.99 se torne R$ 44,99.
 * @param mixed $valor
 * @return string
 */
function BRL($valor) {
    // 1. Remove qualquer símbolo de moeda ou espaço
    // 2. Substitui a vírgula por ponto para que o floatval reconheça os decimais
    $valorLimpo = str_replace(',', '.', preg_replace('/[^\d,.]/', '', $valor));
    
    // 3. Converte para float (ex: "44.99" vira 44.99)
    $valorFloat = floatval($valorLimpo);
    
    // 4. Formata com 2 casas decimais, vírgula para decimal e ponto para milhar
    return 'R$ ' . number_format($valorFloat, 2, ',', '.');
}

/**
 * Formata um CEP para o padrão 00000-000.
 * @param string $cep
 * @return string
 * @throws Exception
 */
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