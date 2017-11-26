<?php

use Ideo\OtpAuth\KeyGenerator;

class KeyGeneratorTest extends TestCase
{

    public function testGenerateRandom()
    {
        $keyGenerator = new KeyGenerator();

        $key1 = $keyGenerator->generateRandom(10);
        $key2 = $keyGenerator->generateRandom(10);

        $this->assertEquals(strlen($key1), 16);
        $this->assertNotEquals($key1, $key2);
    }

}
