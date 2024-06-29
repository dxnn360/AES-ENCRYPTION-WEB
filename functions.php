<?php

function get_key($filepath){
    $key = file_get_contents($filepath);
    return $key;
}

$key = get_key('aes_key.pem');

function str_openssl_enc($str, $iv){
    global $key;
    $cipher = "AES-128-CTR";
    $options = 0;
    return openssl_encrypt($str, $cipher, $key, $options, $iv);
}

function str_openssl_dec($str, $iv){
    global $key;
    $cipher = "AES-128-CTR";
    $options = 0;
    return openssl_decrypt($str, $cipher, $key, $options, $iv);
}
?>
