<?php

use Tesla\JMBG\JMBG;
use Tesla\JMBG\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testFake()
    {
        $gen = new Generator;

        // Test 10 cases
        for ($i = 0; $i < 10; $i++) {
            $this->assertTrue(JMBG::for($gen->fake())->isValid());
        }

        $this->assertEquals('1992-09-25', JMBG::for($gen->fake(25, 9, 992))->getBirthday()->format('Y-m-d'));
        $this->assertEquals('m', JMBG::for($gen->fake(null, null, null, null, 'm'))->getGender());
        $this->assertEquals('f', JMBG::for($gen->fake(null, null, null, null, 'f'))->getGender());
    }
}
