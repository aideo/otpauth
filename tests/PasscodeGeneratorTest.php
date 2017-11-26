<?php

use Base32\Base32;
use Ideo\OtpAuth\HMacSigner;
use Ideo\OtpAuth\PasscodeGenerator;

class PasscodeGeneratorTest extends TestCase
{

    /**
     * @var PasscodeGenerator
     */
    private $passcodeGenerator1;

    /**
     * @var PasscodeGenerator
     */
    private $passcodeGenerator2;

    static function binary_dump($in)
    {
        $l = strlen($in);
        echo "len: {$l}" . PHP_EOL;

        $bindata = unpack("C*", $in);
        $ret = "";
        foreach ($bindata as $v) {
            $ret .= sprintf("%02x ", $v);
        }
        return $ret;
    }

    public function setUp()
    {
        parent::setUp();

        $this->passcodeGenerator1 = new PasscodeGenerator(new HMacSigner(Base32::decode('7777777777777777')));
        $this->passcodeGenerator2 = new PasscodeGenerator(new HMacSigner(Base32::decode('22222222222222222')));
    }

    public function testGenerateResponseCodeLong()
    {
        $response1Long = $this->passcodeGenerator1->generateResponseCode(123456789123456789);

        $this->assertTrue($this->passcodeGenerator1->verifyResponseCode(123456789123456789, $response1Long));
        $this->assertFalse($this->passcodeGenerator1->verifyResponseCode(123456789123456789, 'boguscode'));

        $response1LongNull = $this->passcodeGenerator1->generateResponseCodeFromStateAndChallenge(123456789123456789, null);

        $this->assertEquals($response1Long, $response1LongNull);

        $response1ByteArray = $this->passcodeGenerator1->generateResponseCodeFromChallenge(Base32::decode('AG3JWS5M2BPRK'));

        $this->assertEquals($response1Long, $response1ByteArray);

        $response2Long = $this->passcodeGenerator2->generateResponseCode(123456789123456789);

        $this->assertTrue($this->passcodeGenerator2->verifyResponseCode(123456789123456789, $response2Long));
    }

    public function testRegressionGenerateResponseCode()
    {
        $this->assertEquals('724477', $this->passcodeGenerator1->generateResponseCode(0));
        $this->assertEquals('815107', $this->passcodeGenerator1->generateResponseCode(123456789123456789));
        $this->assertEquals('724477', $this->passcodeGenerator1->generateResponseCodeFromChallenge(Base32::decode('AAAAAAAAAAAAA')));
        $this->assertEquals('815107', $this->passcodeGenerator1->generateResponseCodeFromChallenge(Base32::decode('AG3JWS5M2BPRK')));
        $this->assertEquals('498157', $this->passcodeGenerator1->generateResponseCodeFromStateAndChallenge(123456789123456789, 'challenge'));
    }

}
