<?php

namespace App\Repository\Database\QueryExtension;

use Illuminate\Database\Query\Builder;
use Lib\Pagination\PaginationEntity;

class PaginationQueryExtension implements QueryExtension
{
    private $pagination;

    public function __construct(PaginationEntity $entity)
    {
        $this->pagination = $entity;
    }

    public function extend(Builder $query): Builder
    {
        return $query->forPage(
            $this->pagination->getPage(),
            $this->pagination->getLimit()
        );
    }
}