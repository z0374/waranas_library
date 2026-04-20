<?php

/**
 * Gera uma sequência de 6 dígitos únicos e aleatórios.
 * Foca em performance usando referências de array e shuffle.
 * * @return string
 */
function distinctDigits(): string {
    $digitos = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    
    // Embaralha o array de dígitos (altamente otimizado no core do PHP)
    shuffle($digitos);
    
    // Retorna os 6 primeiros elementos unidos
    return $digitos[0] . $digitos[1] . $digitos[2] . $digitos[3] . $digitos[4] . $digitos[5];
}