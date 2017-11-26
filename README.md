# ideo/otpauth
Provides necessary processing for TOTP.

For pin generation and authentication, it is based on the implementation of google-authenticator.

- [https://github.com/google/google-authenticator](https://github.com/google/google-authenticator)

## Usage
Generation of QR code for TOTP compatible applications.

    // Generate keys randomly, save this key in association with the target account.
    $keyBytes = KeyGenerator::getInstance()->generateRandom(10);
    
    $qrDataUri = QrCodeGenerator::getInstance()->getQrCodeDataUri($keyBytes, 'sample@foo.bar', 'Sample');

Authentication using pin.

    // It reads the key of the target account and compares it with the input pin.
    $generator = new PasscodeGenerator(new HMacSigner($keyBytes));
    $counter = new TotpCounter();
    $time = time();
    
    $valid = $generator->verifyTimeoutCode($pin, $counter->getValueAtTime($time));
