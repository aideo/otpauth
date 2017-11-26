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
     * ランダムなキーを生成します。
     *
     * @param int $length キーの長さ。
     *
     * @return string 生成されたシークレットの値。
     */
    public function generateRandom($length)
    {
        $keyBytes = '';

        for ($i = 0; $i < $length; $i++) {
            $keyBytes .= pack('c', mt_rand(0, 255));
        }

        return Base32::encode($keyBytes);
    }

}
