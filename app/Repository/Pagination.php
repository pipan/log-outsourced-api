<?php

namespace App\Repository;

use Illuminate\Http\Request;

class Pagination
{
    public static function fromRequest(Request $request)
    {
        return [
            'limit' => $request->input('limit', 25),
            'page' => $request->input('page', 1),
            'search' => $request->input('search', '')
        ];
    }
}