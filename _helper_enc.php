<?php

error_reporting(E_ALL);

/*
 Genera un valor para IV
 */
function getIV($method='aes-128-cbc')
{
    $iv_ =openssl_cipher_iv_length($method);
    $rand=openssl_random_pseudo_bytes($iv_);
    return base64_encode($rand);
}
 /*
 Desencripta el texto recibido
 */
function desencriptar($valor, $method='aes-128-cbc', $clave, $iv)
{
    $iv=substr($iv, 0, 16);
    return openssl_decrypt($valor, $method, $clave, false, $iv);
};
 /*
 Encripta el contenido de la variable, enviada como parametro.
*/
function encriptar($valor, $method='aes-128-cbc', $clave, $iv)
{
    $iv=substr($iv, 0, 16);
    return openssl_encrypt($valor, $method, $clave, false, $iv);
};
