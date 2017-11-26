<?php

use Base32\Base32;
use Ideo\OtpAuth\HMacSigner;

class HMacSignerTest extends TestCase
{

    const MESSAGE = 'hello';

    const SECRET = '7777777777777777';

    const SIGNATURE = '2GOH22N7HTHRAC3C4IY24TWH6FEFEOZ7';

    public function testSign()
    {
        $signer = new HMacSigner(Base32::decode(self::SECRET));

        $this->assertEquals(Base32::encode($signer->sign(HMacSignerTest::MESSAGE)), self::SIGNATURE);
    }

}
