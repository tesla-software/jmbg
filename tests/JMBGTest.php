<?php

use Tesla\JMBG\JMBG;
use PHPUnit\Framework\TestCase;

class JMBGTest extends TestCase
{
    public function testIsValid(): void
    {
        $this->assertFalse((new JMBG('0005983225175'))->isValid());
        $this->assertFalse((new JMBG('0000000000001'))->isValid());
        $this->assertFalse((new JMBG('1200000000004'))->isValid());

        $this->assertTrue((new JMBG('2509992391801'))->isValid());
        $this->assertTrue((new JMBG('0101006500006'))->isValid());
        $this->assertTrue((new JMBG('0000000000000'))->isValid());
    }

    public function testStaticCall(): void
    {
        $this->assertTrue(JMBG::for('2509992391801')->isValid());
    }

    public function testGetGender(): void
    {
        $this->assertEquals('m', (new JMBG('0101006500006'))->getGender());
        $this->assertEquals('f', (new JMBG('0101006505016'))->getGender());
    }

    public function testGetBirthday(): void
    {
        $birthday = (new JMBG('0101006500006'))->getBirthday();

        $this->assertInstanceOf(DateTime::class, $birthday);
        $this->assertEquals('2006-01-01', $birthday->format('Y-m-d'));
    }

    public function testSplit(): void
    {
        $chars = (new JMBG())->split('0123456789123');

        $this->assertEquals([
            'A' => '0',
            'B' => '1',
            'C' => '2',
            'D' => '3',
            'E' => '4',
            'F' => '5',
            'G' => '6',
            'H' => '7',
            'I' => '8',
            'J' => '9',
            'K' => '1',
            'L' => '2',
            'M' => '3',
        ], $chars);
    }

    public function testNull(): void
    {
        $this->assertFalse((new JMBG(null))->isValid());
    }
}
