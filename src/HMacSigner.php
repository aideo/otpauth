<?php

namespace Ideo\OtpAuth;

/**
 * HMACSHA1 で著名する Signer 。
 *
 * @package Ideo\OtpAuth
 */
class HMacSigner implements Signer
{

    /**
     * キーを保持します。
     *
     * @var string
     */
    private $key;

    /**
     * キーを指定して HMacSigner を初期化します。
     *
     * @param string $key キーの値。
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @inheritdoc
     */
    public function sign($data)
    {
        return hash_hmac('sha1', $data, $this->key, true);
    }

}
