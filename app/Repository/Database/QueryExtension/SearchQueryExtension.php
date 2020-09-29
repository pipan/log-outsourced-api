<?php

namespace App\Repository\Database\QueryExtension;

use Illuminate\Database\Query\Builder;

class SearchQueryExtension implements QueryExtension
{
    private $columnName;
    private $value;

    public function __construct($columnName, $value)
    {
        $this->columnName = $columnName;
        $this->value = $value;
    }

    public function extend(Builder $query): Builder
    {
        if ($this->columnName === '' || $this->value === '') {
            return $query;
        }
        return $query->where($this->columnName, 'like', $this->value . '%');
    }
}