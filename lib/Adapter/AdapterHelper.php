<?php

namespace Lib\Adapter;

class AdapterHelper
{
    public static function listOf(Adapter $itemAdapter)
    {
        return new ListAdapter($itemAdapter);
    }
}