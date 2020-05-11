<?php

namespace Lib\Generator;

class BinaryGenerator implements Generator
{
    private $length;

    public function __construct($length = 18)
    {
        $this->length = $length;
    }

    public function next()
    {
        return random_bytes($this->length);
    }
}