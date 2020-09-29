<?php

namespace App\Repository\Database\QueryExtension;

use Illuminate\Database\Query\Builder;

interface QueryExtension
{
    public function extend(Builder $quilder): Builder;
}