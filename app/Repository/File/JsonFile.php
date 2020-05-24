<?php

namespace App\Repository\File;

use Illuminate\Support\Facades\Storage;

class JsonFile
{
    private $path;

    public function __construct($file)
    {
        $this->path = "data/" . $file;
    }

    public function read()
    {
        return json_decode(
            Storage::disk('local')->get($this->path),
            true
        );
    }

    public function write($json)
    {
        Storage::disk('local')->put(
            $this->path,
            json_encode($json)
        );
    }
}