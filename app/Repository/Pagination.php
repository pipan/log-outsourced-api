<?php

namespace App\Repository;

use Illuminate\Http\Request;
use Lib\Pagination\PaginationEntity;

class Pagination
{
    public static function fromRequest(Request $request): PaginationEntity
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 25);
        $order = $request->input('order', 'asc');
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }
        return new PaginationEntity([
            'limit' => $limit > 0 ? min($limit, 300) : 25,
            'page' => max($page, 1),
            'search_value' => $request->input('search', ''),
            'order_direction' => $order
        ]);
    }
}