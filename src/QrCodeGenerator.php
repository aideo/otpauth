<?php

namespace Ideo\OtpAuth;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

/**
 * TOTP に対応した QR コードを生成します。
 *
 * @package Ideo\OtpAuth
 */
class QrCodeGenerator
{

    /**
     * QrCodeGenerator オブジェクトを保持します。
     *
     * @var QrCodeGenerator|null
     */
    private static $instance = null;

    /**
     * QrCodeGenerator オブジェクトを取得します。
     *
     * @return QrCodeGenerator 初期化された QrCodeGenerator オブジェクト。
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new QrCodeGenerator();
        }

        return self::$instance;
    }

    /**
     * QR コードを生成します。
     *
     * @param string $secret シークレット。
     * @param string $user ユーザ名。
     * @param string $issuer システム名。
     * @param array $options オプション設定。
     *
     * @return QrCode QrCode オブジェクト。
     */
    public function getQrCode($secret, $user, $issuer, $options = [])
    {
        $user = str_replace(' ', '%20', $user);
        $user = str_replace(':', '%3A', $user);
        $issuer = str_replace(' ', '%20', $issuer);
        $issuer = str_replace(':', '%3A', $issuer);

        $backgroundColor = $options['backgroundColor'] ?: ['r' => 255, 'g' => 255, 'b' => 255];
        $foregroundColor = $options['foregroundColor'] ?: ['r' => 0, 'g' => 0, 'b' => 0];
        $size = $options['size'] ?: 300;

        $endpoint = "otpauth://totp/{$issuer}:{$user}?secret={$secret}&issuer={$issuer}";

        return (new QrCode())
            ->setBackgroundColor($backgroundColor)
            ->setEncoding('utf-8')
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::LOW)
            ->setForegroundColor($foregroundColor)
            ->setSize($size)
            ->setText($endpoint)
            ->setValidateResult(false);
    }

    /**
     * QR コード画像を表す data-uri を取得します。
     *
     * @param string $secret シークレット。
     * @param string $user ユーザ名。
     * @param string $issuer システム名。
     * @param array $options オプション設定。
     *
     * @return string QR コードを表す data-uri 文字列。
     */
    public function getQrCodeDataUri($secret, $user, $issuer, $options = [])
    {
        return $this->getQrCode($secret, $user, $issuer, $options)->writeDataUri();
    }

}
