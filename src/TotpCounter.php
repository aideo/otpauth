<?php

namespace Ideo\OtpAuth;

/**
 * Class TotpCounter
 *
 * Google Authenticator の TotpCounter クラスを移植したものです。
 * https://github.com/google/google-authenticator-android/blob/master/AuthenticatorApp/src/main/java/com/google/android/apps/authenticator/TotpCounter.java
 *
 * @package Ideo\OtpAuth
 */
class TotpCounter
{

    /**
     * このカウンタの値が 0 となる最も早い時刻。
     *
     * @var int
     */
    private $startTime;

    /**
     * カウンタの値が変化する秒数。
     *
     * @var int
     */
    private $timeStep;

    /**
     * TotpCounter オブジェクトを初期化します。
     *
     * @param int $timeStep カウンタの値を変化させる秒数。
     * @param int $startTime このカウンタの値が 0 となる最も早い時刻。
     */
    public function __construct($timeStep = 30, $startTime = 0)
    {
        $this->timeStep = $timeStep;
        $this->startTime = $startTime;
    }

    /**
     * このカウンタが 0 を取る最も早い時刻を取得します。
     *
     * @return int 時間（UNIXエポックからの秒数）。
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * このカウンタの値が変化する頻度を取得します。
     *
     * @return int このカウンタの値が連続的に変化する間隔（秒）。
     */
    public function getTimeStep()
    {
        return $this->timeStep;
    }

    /**
     * 指定した時刻のカウンタの値を取得します。
     *
     * @param int $time 値を取得する UNIX 時刻。
     *
     * @return float カウンタの値。
     */
    public function getValueAtTime($time)
    {
        $timeSinceStartTime = $time - $this->startTime;

        // 開始時刻からの結果により式を分岐。
        if ($timeSinceStartTime >= 0) {
            return intval($timeSinceStartTime / $this->timeStep);
        } else {
            return intval(($timeSinceStartTime - ($this->timeStep - 1)) / $this->timeStep);
        }
    }

    /**
     * カウンタが指定された値をとる時間を取得します。
     *
     * @param int $value 値。
     *
     * @return int カウンタが値をとるときの最も早い時刻（UNIXエポックからの秒数）。
     */
    public function getValueStartTime($value)
    {
        return $this->startTime + ($value * $this->timeStep);
    }

}
