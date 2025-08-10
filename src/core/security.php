<?php


function aesEncrypt($data, $key) {
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = random_bytes($iv_length);
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

function aesDecrypt($data, $key) {
    $decoded_data = base64_decode($data);
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($decoded_data, 0, $iv_length);
    $encrypted_data = substr($decoded_data, $iv_length);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}