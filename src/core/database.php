<?php
/**
 * Busca um registro em uma tabela do banco de dados MySQL.
 */
function arquivoBd($tbl, $cln, $ref, $con) {
    // $con deve ser um array com as credenciais: [host, user, pass, dbname]
    $conn = new mysqli($con[0], $con[1], $con[2], $con[3]);
    $conn->set_charset('utf8mb4');

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM `{$tbl}` WHERE `{$cln}` = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    
    $stmt->bind_param("s", $ref);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = [];
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        // É melhor retornar um array vazio ou null do que dar 'echo' dentro de uma função.
        // echo "Arquivo não encontrado.";
    }

    $stmt->close();
    $conn->close();
    return $row;
}