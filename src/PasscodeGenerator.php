<?php

namespace Ideo\OtpAuth;

use LogicException;

/**
 * パスコードの生成を行います。
 *
 * Google Authenticator の PasscodeGenerator クラスを移植したものです。
 * https://github.com/google/google-authenticator-android/blob/master/AuthenticatorApp/src/main/java/com/google/android/apps/authenticator/PasscodeGenerator.java
 *
 * @package Ideo\OtpAuth
 */
class PasscodeGenerator
{

    /**
     * チェックする前後の間隔。
     *
     * @var int
     */
    const ADJACENT_INTERVALS = 1;

    /**
     * 10のべき乗の配列。
     *
     * @var int[]
     */
    const DIGITS_POWER = [1, 10, 100, 1000, 10000, 100000, 1000000, 10000000, 100000000, 1000000000];

    /**
     * パスコードの最大長を表します。
     *
     * @var int
     */
    const MAX_PASSCODE_LENGTH = 9;

    /**
     * デフォルトのパスコード長。
     *
     * @var int
     */
    const PASS_CODE_LENGTH = 6;

    /**
     * パスコードの長さを表します。
     *
     * @var int
     */
    private $codeLength;

    /**
     * Signer を保持します。
     *
     * @var Signer
     */
    private $signer;

    /**
     * PasscodeGenerator を初期化します。
     *
     * @param Signer $signer Signer オブジェクト。
     * @param int $passCodeLength コードの長さ。
     */
    public function __construct(Signer $signer, $passCodeLength = self::PASS_CODE_LENGTH)
    {
        if (($passCodeLength < 0) || ($passCodeLength > self::MAX_PASSCODE_LENGTH)) {
            throw new LogicException('PassCodeLength must be between 1 and ' . self::MAX_PASSCODE_LENGTH . ' digits.');
        }

        $this->signer = $signer;
        $this->codeLength = $passCodeLength;
    }

    /**
     * 状態からコードを取得します。
     *
     * @param int $state 状態を表す 8 バイトの整数。
     *
     * @return string コードの値。
     */
    public function generateResponseCode($state)
    {
        $state = str_pad(pack('J', $state), 8, chr(0), STR_PAD_LEFT);

        return $this->generateResponseCodeFromChallenge($state);
    }

    /**
     * チャレンジからコードを取得します。
     *
     * @param string $challenge チャレンジとして使用するバイト列。
     *
     * @return string コード。
     */
    public function generateResponseCodeFromChallenge($challenge)
    {
        $hash = $this->signer->sign($challenge);

        $offset = ord(substr($hash, -1)) & 0xF;
        $truncatedHash = $this->hashToInt($hash, $offset) & 0x7FFFFFFF;
        $pinValue = $truncatedHash % self::DIGITS_POWER[$this->codeLength];

        return $this->padOutput($pinValue);
    }

    /**
     * 状態とチャレンジからコードを取得します。
     *
     * @param int $state 状態を表す 8 バイトの整数。
     * @param string $challenge チャレンジとして使用するバイト列。
     *
     * @return string コード。
     */
    public function generateResponseCodeFromStateAndChallenge($state, $challenge)
    {
        if ($challenge === null) {
            return $this->generateResponseCode($state);
        } else {
            $value = str_pad(pack('J', $state), 8, chr(0), STR_PAD_LEFT) . $challenge;

            return $this->generateResponseCodeFromChallenge($value);
        }
    }

    /**
     * 認証を行います。
     *
     * @param string $challenge チャレンジ。
     * @param string $response 確認するレスポンス。
     *
     * @return bool 有効な場合 true, それ以外は false 。
     */
    public function verifyResponseCode($challenge, $response)
    {
        $expectedResponse = $this->generateResponseCode($challenge);

        return $expectedResponse === $response;
    }

    /**
     * タイムアウトコードを確認します。
     * タイムアウトコードは、インターバル期間とチェックされた隣接間隔の数によって決定される時間に有効です。
     *
     * @param string $timeoutCode タイムアウトコード。
     * @param int $currentInterval 現在の間隔。
     * @param int $pastIntervals チェックする過去の間隔の数。
     * @param int $futureIntervals チェックする未来の間隔の数。
     *
     * @return bool 有効な場合 true, それ以外は false 。
     */
    public function verifyTimeoutCode($timeoutCode, $currentInterval, $pastIntervals = self::ADJACENT_INTERVALS, $futureIntervals = self::ADJACENT_INTERVALS)
    {
        $pastIntervals = max($pastIntervals, 0);
        $futureIntervals = max($futureIntervals, 0);

        for ($i = -$pastIntervals; $i <= $futureIntervals; ++$i) {
            $candidate = $this->generateResponseCode($currentInterval - $i);

            if ($candidate === $timeoutCode) {
                return true;
            }
        }

        return false;
    }

    /**
     * 配列の指定された位置から整数値を取得します。
     *
     * @param string $bytes 整数値を取得する配列。
     * @param int $start 取得を開始する位置。
     *
     * @return int 取得された整数値。
     */
    private function hashToInt($bytes, $start)
    {
        $input = substr($bytes, $start, (strlen($bytes) - $start));
        $val = unpack('Nint', substr($input, 0, 4));

        return $val['int'];
    }

    /**
     * コードを0埋めします。
     *
     * @param int $value 0埋めする値。
     *
     * @return string 0埋めされた値。
     */
    private function padOutput($value)
    {
        return str_pad((string)$value, $this->codeLength, '0', STR_PAD_LEFT);
    }

}
