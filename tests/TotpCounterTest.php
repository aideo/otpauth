<?php

use Ideo\OtpAuth\TotpCounter;

class TotpCounterTest extends TestCase
{

    public function testConstruct_withDefaultStartTime()
    {
        $counter = new TotpCounter(11);

        $this->assertEquals(11, $counter->getTimeStep());
        $this->assertEquals(0, $counter->getStartTime());
    }

    public function testConstruct_withDurationAndStartTime()
    {
        $counter = new TotpCounter(3, 7);

        $this->assertEquals(3, $counter->getTimeStep());
        $this->assertEquals(7, $counter->getStartTime());
    }

    public function testGetValueAtTime()
    {
        $counter = new TotpCounter(7, 123);

        $this->assertEquals(-18, $counter->getValueAtTime(0));
        $this->assertEquals(-2, $counter->getValueAtTime(115));
        $this->assertEquals(-1, $counter->getValueAtTime(116));
        $this->assertEquals(-1, $counter->getValueAtTime(117));
        $this->assertEquals(-1, $counter->getValueAtTime(122));
        $this->assertEquals(0, $counter->getValueAtTime(123));
        $this->assertEquals(0, $counter->getValueAtTime(124));
        $this->assertEquals(0, $counter->getValueAtTime(129));
        $this->assertEquals(1, $counter->getValueAtTime(130));
        $this->assertEquals(1, $counter->getValueAtTime(131));
        $this->assertEquals(100, $counter->getValueAtTime(823));
        $this->assertEquals(10000000000, $counter->getValueAtTime(70000000123));
    }

    public function testGetValueAtTime_withTimeBeforeStartTime()
    {
        $counter = new TotpCounter(3, 11);

        $this->assertEquals(-1, $counter->getValueAtTime(10));
    }

}
