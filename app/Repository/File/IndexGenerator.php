<?php

namespace App\Repository\File;

use Lib\Generator\Generator;

class IndexGenerator implements Generator
{
    private $key;
    private $jsonFile;

    public function __construct($key)
    {
        $this->key = $key;
        $this->jsonFile = new JsonFile('increment.json');
    }

    public function next()
    {
        $increments = $this->jsonFile->read();
        if (!isset($increments[$this->key])) {
            $increments[$this->key] = 0;
        }
        $increments[$this->key]++;

        $this->jsonFile->write($increments);

        return $increments[$this->key];
    }
}