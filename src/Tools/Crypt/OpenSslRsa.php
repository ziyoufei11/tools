<?php
declare(strict_types=1);

namespace Lss\EasyTools\Tools\Crypt;

class OpenSslRsa
{
    const ENCRYPT_LEN = 32;

    private static $publicKey = '-----BEGIN PUBLIC KEY-----
-----END PUBLIC KEY-----'
    ;

    private static $privateKey = '-----BEGIN PRIVATE KEY-----
-----END PRIVATE KEY-----';

    /**
     * 私钥加密
     * @param $dataContent
     * @param $privateKey
     * @return string
     */
    public static function encryptedByPrivateKey($dataContent, $privateKey = null)
    {
        $privateKey  = $privateKey??self::$privateKey;
        $dataContent = base64_encode($dataContent);
        $encrypted   = "";
        $totalLen    = strlen($dataContent);
        $encryptPos  = 0;
        while ($encryptPos < $totalLen) {
            openssl_private_encrypt(substr($dataContent, $encryptPos, self::ENCRYPT_LEN), $encryptData, $privateKey);
            $encrypted  .= bin2hex($encryptData);
            $encryptPos += self::ENCRYPT_LEN;
        }
        return $encrypted;
    }

    /**
     * 私钥解密
     * @param $encrypted
     * @return bool|false|string
     */
    public static function decryptByPrivateKey($encrypted, $privateKey = null)
    {
        $privateKey = $privateKey??self::$privateKey;
        $decrypt    = "";
        $totalLen   = strlen($encrypted);
        $decryptPos = 0;
        while ($decryptPos < $totalLen) {
            openssl_private_decrypt(hex2bin(substr($encrypted, $decryptPos, self::ENCRYPT_LEN * 8)), $decryptData, $privateKey);
            $decrypt    .= $decryptData;
            $decryptPos += self::ENCRYPT_LEN * 8;
        }
        $decrypt = base64_decode($decrypt);
        return $decrypt;
    }

    /**
     * 公钥加密
     * @param $dataContent
     * @return string
     */
    public static function encryptedByPublicKey($dataContent, $publicKey = null)
    {
        $publicKey  = $publicKey??self::$publicKey;
        $dataContent = base64_encode($dataContent);
        $encrypted   = "";
        $totalLen    = strlen($dataContent);
        $encryptPos  = 0;
        while ($encryptPos < $totalLen) {
            openssl_public_encrypt(substr($dataContent, $encryptPos, self::ENCRYPT_LEN), $encryptData, $publicKey);
            $encrypted  .= bin2hex($encryptData);
            $encryptPos += self::ENCRYPT_LEN;
        }
        return $encrypted;
    }

    /**
     * 公钥解密
     * @param $encrypted
     * @param $publicKey
     * @return bool|false|string
     */
    public static function decryptByPublicKey($encrypted, $publicKey = null)
    {
        $publicKey  = $publicKey??self::$publicKey;
        $decrypt    = "";
        $totalLen   = strlen($encrypted);
        $decryptPos = 0;
        while ($decryptPos < $totalLen) {
            openssl_public_decrypt(hex2bin(substr($encrypted, $decryptPos, self::ENCRYPT_LEN * 8)), $decryptData, $publicKey);
            $decrypt    .= $decryptData;
            $decryptPos += self::ENCRYPT_LEN * 8;
        }
        //openssl_public_decrypt($encrypted, $decryptData, $publicKey);
        $decrypt = base64_decode($decrypt);
        return $decrypt;
    }
}
