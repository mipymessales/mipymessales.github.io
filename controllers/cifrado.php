<?php

class cifrado
{
private static $clave_secreta = 'mi_clave_super_segura_2025';
function cifrar_url($url, $key) {
    $method = 'AES-256-CBC';
    $iv = openssl_random_pseudo_bytes(16); // 16 bytes para AES
    $encrypted = openssl_encrypt($url, $method, $key, 0, $iv);
    $token = base64_encode($iv . $encrypted);
    return $token;
}

    /**
     * @return string
     */
    public static function getClaveSecreta(): string
    {
        return self::$clave_secreta;
    }

// Debe tener 32 caracteres para AES-256


//$token = cifrar_id($id_producto, $clave_secreta);



function descifrar_token($token, $key) {
    $method = 'AES-256-CBC';
    $datos = base64_decode($token);
    if ($datos === false || strlen($datos) <= 16) return false;

    $iv = substr($datos, 0, 16);
    $encrypted = substr($datos, 16);

    $url = openssl_decrypt($encrypted, $method, $key, 0, $iv);
    return $url;
}

}
?>