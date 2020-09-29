<?php

namespace App\Repository\Database\QueryExtension;

use Illuminate\Database\Query\Builder;

class ChainQueryExtension implements QueryExtension
{
    private $extensions;

    public function __construct($extensions)
    {
        $this->extensions = $extensions;
    }

    public function extend(Builder $query): Builder
    {
        foreach ($this->extensions as $extension) {
            $query = $extension->extend($query);
        }
        return $query;
    }
}