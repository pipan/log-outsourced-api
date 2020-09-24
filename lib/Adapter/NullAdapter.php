<?php

namespace Lib\Adapter;

abstract class NullAdapter implements Adapter
{
    public function adapt($item)
    {
        if ($item === null) {
            return $this->adaptNull();
        }
        return $this->adaptNotNull($item);
    }

    protected function adaptNull()
    {
        return null;
    }

    abstract protected function adaptNotNull($item);
}