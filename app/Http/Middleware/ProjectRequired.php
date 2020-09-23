<?php

namespace App\Http\Middleware;

use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use Closure;
use Illuminate\Support\Facades\Validator;

class ProjectRequired
{
    private $errorSchema;

    public function __construct()
    {
        $this->errorSchema = new ValidationErrorResponseSchema();
    }

    public function handle($request, Closure $next)
    {
        $validation = Validator::make($request->all(), [
            'project_uuid' => ['bail', 'required']
        ]);
        if ($validation->fails()) {
            return response($this->errorSchema->adapt($validation->errors()), 422);
        }

        return $next($request);
    }
}