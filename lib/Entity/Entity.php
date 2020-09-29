<?php

namespace Lib\Entity;

use Illuminate\Support\Arr;

abstract class Entity
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    abstract protected function create($data);

    public function toArray()
    {
        return $this->data;
    }

    public function with($key, $value)
    {
        $data = $this->toArray();
        Arr::set($data, $key, $value);
        return $this->create($data);
    }
}