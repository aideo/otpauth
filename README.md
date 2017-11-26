# ideo/otpauth
[![Build Status](https://travis-ci.org/aideo/otpauth.svg?branch=master)](https://travis-ci.org/aideo/otpauth)

Provides necessary processing for TOTP.

For pin generation and authentication, it is based on the implementation of google-authenticator.

- [https://github.com/google/google-authenticator](https://github.com/google/google-authenticator)

## Usage
Generation of QR code for TOTP compatible applications.

    $keyGenerator = new KeyGenerator();
    $qrCodeGenerator = new QrCodeGenerator();

    // Generate keys randomly, save this key in association with the target account.
    $keyBytes = $keyGenerator->generateRandom(10);
    
    $qrDataUri = $qrCodeGenerator->getQrCodeDataUri($keyBytes, 'sample@foo.bar', 'Sample');

Authentication using pin.

    // It reads the key of the target account and compares it with the input pin.
    $passcodeGenerator = new PasscodeGenerator(new HMacSigner($keyBytes));
    $counter = new TotpCounter();
    $time = time();
    
    $valid = $passcodeGenerator->verifyTimeoutCode($pin, $counter->getValueAtTime($time));
