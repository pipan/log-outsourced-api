<?php

namespace Tests\Generator;

use Lib\Generator\UidGenerator;
use Tests\TestCase;

class UidGeneratorTest extends TestCase
{
    public function testGenerates16ByteString()
    {
        $generator = new UidGenerator();
        $this->assertEquals(16, strlen($generator->next()));
    }
}