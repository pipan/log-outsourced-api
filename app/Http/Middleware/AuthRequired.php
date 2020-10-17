<?php

namespace App\Http\Middleware;

use App\Http\ResponseError;
use App\Repository\Repository;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use UnexpectedValueException;

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
            return ResponseError::unauthorized();
        }

        try {
            $token = substr($request->header('Authorization', 'Bearer '), 7);
            $tokenData = JWT::decode($token, config('app.key'), ['HS256']);
        } catch (ExpiredException $ex) {
            return ResponseError::unauthorized();
        } catch (Exception $ex) {
            Log::info('Unauthorized exception: ' . $ex->getMessage());
            return ResponseError::unauthorized();
        }

        if ($tokenData->sub === 'root') {
            return $next($request);
        }

        $administrator = $this->repository->administrator()
            ->get($tokenData->sub);
        
        if (!$administrator) {
            return ResponseError::unauthorized();
        }

        return $next($request);
    }
}