<?php

namespace Lib\Adapter;

class ListAdapter implements Adapter
{
    private $itemAdapter;

    public function __construct(Adapter $itemAdapter)
    {
        $this->itemAdapter = $itemAdapter;
    }

    public function adapt($items)
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $this->itemAdapter->adapt($item);
        }
        return $result;
    }
}