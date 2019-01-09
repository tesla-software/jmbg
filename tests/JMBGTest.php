<?php

use Tesla\JMBG\JMBG;
use PHPUnit\Framework\TestCase;

class JMBGTest extends TestCase
{
    public function testIsValid()
    {
        $this->assertFalse((new JMBG('0123456789123'))->isValid());
        $this->assertFalse((new JMBG('0000000000000'))->isValid());
        $this->assertFalse((new JMBG('1200000000003'))->isValid());

        $this->assertTrue((new JMBG('2509992391801'))->isValid());
        $this->assertTrue((new JMBG('0101006500006'))->isValid());
    }

    public function testGetGender()
    {
        $this->assertEquals('m', (new JMBG('0101006500006'))->getGender());
        $this->assertEquals('f', (new JMBG('0101006505016'))->getGender());
    }

    public function testGetBirthday()
    {
        $birthday = (new JMBG('0101006500006'))->getBirthday();

        $this->assertInstanceOf(DateTime::class, $birthday);
        $this->assertEquals('2006-01-01', $birthday->format('Y-m-d'));
    }

    public function testSplit()
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
}
