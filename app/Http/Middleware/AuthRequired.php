<?php

namespace App\Http\Middleware;

use App\Domain\Administrator\AdministratorEntity;
use App\Repository\Repository;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthRequired
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            Log::info("no header");
            return response([], 401);
        }

        try {
            $token = substr($request->header('Authorization', 'Bearer '), 7);
            $tokenData = JWT::decode($token, config('app.key'), ['HS256']);
        } catch (ExpiredException $ex) {
            return response([], 401);
        } catch (Exception $ex) {
            return response($ex->getMessage(), 500);
        }

        if ($tokenData->sub === 'root') {
            return $next($request);
        }

        $administrator = $this->repository->administrator()
            ->get($tokenData->sub);
        
        if (!$administrator) {
            return response([], 401);
        }

        return $next($request);
    }
}