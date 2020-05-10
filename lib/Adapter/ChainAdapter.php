<?php

namespace Lib\Adapter;

class ChainAdapter implements Adapter
{
    private $adapters;

    public function __construct($adapters = [])
    {
        $this->adapters = $adapters;
    }

    public function adapt($item)
    {
        $result = $item;
        foreach ($this->adapters as $adapter) {
            $result = $adapter->adapt($result);
        }
        return $result;
    }
}