<?php

namespace Lib\Generator;

class UidGenerator implements Generator
{
    public function next()
    {
        return random_bytes(16);
    }
}