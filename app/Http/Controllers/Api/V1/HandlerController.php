<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\ResponseSchema\HandlerResponseSchemaAdapter;

class HandlerController
{
    private $schema;

    public function __construct()
    {
        $this->schema = new HandlerResponseSchemaAdapter();
    }

    public function index()
    {

    }

    public function view($slug)
    {

    }
}