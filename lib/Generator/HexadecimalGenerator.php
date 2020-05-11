<?php

namespace Lib\Generator;

class HexadecimalGenerator implements Generator
{
    private $binaryGenertor;

    public function __construct($byteLength = 18)
    {
        $this->binaryGenertor = new BinaryGenerator($byteLength);
    }

    public function next()
    {
        return bin2hex($this->binaryGenertor->next());
    }
}