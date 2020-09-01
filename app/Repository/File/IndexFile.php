<?php

namespace App\Repository\File;

use App\Repository\File\JsonFile;

class IndexFile
{
    private $jsonFile;

    public function __construct($indexName)
    {
        $this->jsonFile = new JsonFile($indexName . ".json");
    }

    public function exists($key)
    {
        $json = $this->jsonFile->read();
        return isset($json[$key]);
    }

    public function find($key)
    {
        $json = $this->jsonFile->read();
        return $json[$key] ?? null;
    }

    public function set($key, $value)
    {
        $json = $this->jsonFile->read();
        $json[$key] = $value;
        $this->jsonFile->write($json);
    }

    public function delete($key)
    {
        $json = $this->jsonFile->read();
        unset($json[$key]);
        $this->jsonFile->write($json);
    }
}