<?php

namespace Tests\Unit\Generator;

use Lib\Generator\HexadecimalGenerator;
use Tests\TestCase;

class HexadecimalGeneratorTest extends TestCase
{
    public function testGenerates16ByteString()
    {
        $generator = new HexadecimalGenerator(16);
        $this->assertEquals(32, strlen($generator->next()));
    }

    public function testGeneratesHexadecimal()
    {
        $generator = new HexadecimalGenerator(16);

        $result = $generator->next();

        $this->assertEquals(16, strlen(hex2bin($result)));
    }
}