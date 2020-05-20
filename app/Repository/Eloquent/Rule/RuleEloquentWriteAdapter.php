<?php

namespace App\Repository\Eloquent\Rule;

use Lib\Adapter\Adapter;

class RuleEloquentWriteAdapter implements Adapter
{
    public function adapt($item)
    {
        $rule = new Rule();
        $rule->pattern = trim($item);
        return $rule;
    }
}