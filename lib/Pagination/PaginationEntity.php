<?php

namespace Lib\Pagination;

use Illuminate\Support\Facades\Validator;
use Lib\Entity\Entity;
use Lib\Entity\InvalidEntityException;

class PaginationEntity extends Entity
{
    public function __construct($data)
    {
        parent::__construct([
            'page' => intval($data['page'] ?? 1),
            'limit' => intval($data['limit'] ?? 25),
            'search_column' => $data['search_column'] ?? '',
            'search_value' => $data['search_value'] ?? '',
            'order_column' => $data['order_column'] ?? 'id',
            'order_direction' => $data['order_direction'] ?? 'asc'
        ]);

        $validator = Validator::make($this->toArray(), [
            'page' => ['required', 'integer', 'min:1'],
            'limit' => ['required', 'integer', 'min:1', 'max:300']
        ]);
        if ($validator->fails()) {
            throw new InvalidEntityException('Pagination entity is invalid');
        }
    }

    public function getPage()
    {
        return $this->data['page'];
    }

    public function getLimit()
    {
        return $this->data['limit'];
    }

    public function getSearchValue()
    {
        return $this->data['search_value'];
    }

    public function getSearchBy()
    {
        return $this->data['search_column'];
    }

    public function getOrderBy()
    {
        return $this->data['order_column'];
    }

    public function getOrderDirection()
    {
        return $this->data['order_direction'];
    }

    private function with($key, $value)
    {
        $data = $this->toArray();
        $data[$key] = $value;
        return new PaginationEntity($data);
    }

    public function searchBy($value)
    {
        return $this->with('search_column', $value);
    }

    public function orderBy($value)
    {
        return $this->with('order_column', $value);
    }
}