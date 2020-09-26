<?php

namespace Lib\Entity;

use Lib\Adapter\NullAdapter;

class EntityWhitelistAdapter extends NullAdapter
{
    private $whitelist;

    public function __construct($whitelist)
    {
        $this->whitelist = $whitelist;
    }

    protected function adaptNotNull($item)
    {
        $data = $item->toArray();
        $result = [];
        foreach ($this->whitelist as $key) {
            if (!isset($data[$key])) {
                continue;
            }
            $result[$key] = $data[$key];
        }
        return $result;
    }
}