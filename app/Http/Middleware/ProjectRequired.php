<?php

namespace App\Http\Middleware;

use App\Domain\ExistsRule;
use App\Domain\Project\UuidExistsValidator;
use App\Repository\Repository;
use Closure;
use Illuminate\Support\Facades\Validator;

class ProjectRequired
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;    
    }

    public function handle($request, Closure $next)
    {
        $uuiExists = new ExistsRule(
            new UuidExistsValidator($this->repository->project())
        );
        $validation = Validator::make($request->all(), [
            'project_uuid' => ['bail', 'required', $uuiExists]
        ]);
        if ($validation->fails()) {
            return response([], 404);
        }

        return $next($request);
    }
}