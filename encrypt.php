<?php


error_reporting(E_ALL);
$url='https://apitest.dtes.mh.gob.sv/seguridad/auth';
$user="12172401201018";
$pwd="Apache\$xx2023";
//Configuracion del algoritmo de encriptacion
//Debes cambiar esta cadena, debe ser larga y unica
//nadie mas debe conocerla
$clave  = 'In the country of the blind, the one-eyed man is king';
//Metodo de encriptacion
$method = 'aes-256-cbc';
// Puedes generar una diferente usando la funcion $getIV()
$iv = "C9fBxl1EWtYTL1/M";
 /*
 Genera un valor para IV
 */
function getIV($method)
{
    return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
}
 /*
 Desencripta el texto recibido
 */
function desencriptar($valor, $method, $clave, $iv)
{
    return openssl_decrypt($valor, $method, $clave, false, $iv);
};
 /*
 Encripta el contenido de la variable, enviada como parametro.
*/
function encriptar($valor, $method, $clave, $iv)
{
    return openssl_encrypt($valor, $method, $clave, false, $iv);
};
$iv2=getIV($method);
$dato = $pwd;
//Encripta informacion:
$dato_encriptado = encriptar($pwd, $method, $clave, $iv2);
//Desencripta informacion:
$dato_desencriptado = desencriptar($dato_encriptado, $method, $clave, $iv2);
echo 'Dato encriptado: '. $dato_encriptado . '<br>';
echo 'Dato desencriptado: '. $dato_desencriptado . '<br>';
echo "IV manual: " . $iv. "IV generado: " . $iv2;
