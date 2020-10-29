<?php

namespace Tests\Unit\Generator;

use Lib\Generator\BinaryGenerator;
use Tests\TestCase;

class BinaryGeneratorTest extends TestCase
{
    public function testGenerates16ByteString()
    {
        $generator = new BinaryGenerator(16);
        $this->assertEquals(16, strlen($generator->next()));
    }
}