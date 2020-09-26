<?php

namespace Lib\Entity;

use Lib\Adapter\NullAdapter;

class EntityBlacklistAdapter extends NullAdapter
{
    private $blacklist;

    public function __construct($keys)
    {
        $this->blacklist = $keys;
    }

    protected function adaptNotNull($item)
    {
        $data = $item->toArray();
        $result = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $this->blacklist)) {
                continue;
            }
            $result[$key] = $value;
        }
        return $result;
    }
}