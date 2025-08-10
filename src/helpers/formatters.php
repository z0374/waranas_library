<?php
function BRL($valor) {
    $valor = floatval(preg_replace('/[^\d,.]/', '', $valor));
    return 'R$ ' . number_format($valor, 2, ',', '.');
}