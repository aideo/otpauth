<?php

namespace Ideo\OtpAuth;

use Base32\Base32;

/**
 * キーの生成をサポートします。
 *
 * @package Ideo\OtpAuth
 */
class KeyGenerator
{

    /**
     * KeyGenerator オブジェクトを保持します。
     *
     * @var KeyGenerator|null
     */
    private static $instance = null;

    /**
     * KeyGenerator オブジェクトを取得します。
     *
     * @return KeyGenerator 初期化された KeyGenerator オブジェクト。
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new KeyGenerator();
        }

        return self::$instance;
    }

    /**
     * ランダムなキーを生成します。
     *
     * @param int $length キーの長さ。
     *
     * @return string 生成されたシークレットの値。
     */
    public function generateRandom($length)
    {
        $secret = '';

        for ($i = 0; $i < $length; $i++) {
            $secret .= pack('c', mt_rand(0, 255));
        }

        return Base32::encode($secret);
    }

}
