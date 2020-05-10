<?php

namespace Lib\Adapter;

class AdapterHelper
{
    public static function listOf(Adapter $itemAdapter)
    {
        return new ListAdapter($itemAdapter);
    }

    public static function chain($adapters = [])
    {
        return new ChainAdapter($adapters);
    }
}