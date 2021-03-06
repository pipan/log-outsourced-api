<?php

namespace App\Http\Controllers\Api\V1;

use Lib\Entity\Entity;
use Lib\Pagination\PaginationEntity;

class ListMetaEntity extends Entity
{
    public static function fromPagination(PaginationEntity $entity)
    {
        return new ListMetaEntity([
            'pagination' => [
                'page' => $entity->getPage(),
                'limit' => $entity->getLimit()
            ]
        ]);
    }

    protected function create($data)
    {
        return new ListMetaEntity($data);
    }

    public function withPaginationMax($value)
    {
        return $this->with('pagination.max', $value);
    }

    public function withTotalItems($value)
    {
        $max = intval(ceil($value / $this->data['pagination']['limit']));
        return $this->with('pagination.total', $value)
            ->withPaginationMax($max);
    }
}