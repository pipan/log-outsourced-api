<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Config\ConfigEntity;
use App\Http\ResponseSchema\ConfigResponseSchemaAdapter;
use App\Repository\Repository;
use Illuminate\Http\Request;

class ConfigController
{
    private $responseSchemaAdapter;

    public function __construct()
    {
        $this->responseSchemaAdapter = new ConfigResponseSchemaAdapter();
    }

    public function index(Repository $repository)
    {
        $config = $repository->config()->load();

        return response()->json(
            $this->responseSchemaAdapter->adapt($config),
            200
        );
    }

    public function update(Request $request, Repository $repository)
    {
        $config = $repository->config()->load();

        $config = new ConfigEntity([
            'services' => $request->input('services', $config->get('services', []))
        ]);
    }
}