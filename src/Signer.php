<?php

namespace Ideo\OtpAuth;

/**
 * 署名実装を提供します。
 *
 * Google Authenticator の Signer インターフェースを移植したものです。
 * https://github.com/google/google-authenticator-android/blob/master/AuthenticatorApp/src/main/java/com/google/android/apps/authenticator/PasscodeGenerator.java
 *
 * @package Ideo\OtpAuth
 */
interface Signer
{

    /**
     * 署名を行います。
     *
     * @param string $data 署名するデータ。
     *
     * @return string 署名されたデータ。
     */
    public function sign($data);

}
