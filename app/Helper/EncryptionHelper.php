<?php

namespace App\Helpers;

class EncryptionHelper
{
    private static $key;
    private static $cipher = 'AES-256-CBC';

    public static function setKey()
    {
        self::$key = env('ENCRYPTION_KEY', 'default-key'); // Use the key from .env
    }

    public static function encrypt($data)
    {
        self::setKey();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$cipher));
        $encrypted = openssl_encrypt($data, self::$cipher, self::$key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    public static function decrypt($data)
    {
        self::setKey();
        list($encryptedData, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encryptedData, self::$cipher, self::$key, 0, $iv);
    }
}
