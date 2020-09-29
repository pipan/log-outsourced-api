<?php

namespace App\Repository\Database\QueryExtension;

use Illuminate\Database\Query\Builder;
use Lib\Pagination\PaginationEntity;

class OrderQueryExtension implements QueryExtension
{
    private $pagination;

    public function __construct(PaginationEntity $entity)
    {
        $this->pagination = $entity;
    }

    public function extend(Builder $query): Builder
    {
        if ($this->pagination->getOrderBy() === '' || $this->pagination->getOrderDirection() === '') {
            return $query;
        }
        return $query->orderBy(
            $this->pagination->getOrderBy(),
            $this->pagination->getOrderDirection()
        );
    }
}