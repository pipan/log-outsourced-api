<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\Adapter;

class ValidationErrorResponseSchema implements Adapter
{
    public function adapt($item)
    {
        return [
            'errors' => $item->toArray()
        ];
    }
}
