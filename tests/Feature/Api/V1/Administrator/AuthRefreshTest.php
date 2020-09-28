<?php

namespace Tests\Feature\Api\V1\Administrator;

use Firebase\JWT\JWT;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class AuthRefreshTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AdministratorTestSeeder::seed($this->administratorRepository);
    }

    public function testResponseOk()
    {
        $refresh = JWT::encode([
            'sub' => 1,
            'exp' => time() + 3600
        ], config('app.key'));

        $response = $this->post('api/v1/auth/refresh', [
            'refresh_token' => $refresh
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access', 'refresh']);
        $response->assertCookieNotExpired('refresh');
    }

    public function testResponseUnauthorizedIfExpired()
    {
        $refresh = JWT::encode([
            'sub' => 1,
            'exp' => time() - 3600
        ], config('app.key'));

        $response = $this->post('api/v1/auth/refresh', [
            'refresh_token' => $refresh
        ]);

        $response->assertStatus(401);
    }

    public function testResponse500IfModified()
    {
        $refresh = JWT::encode([
            'sub' => 1,
            'exp' => time() + 3600
        ], config('app.key'));

        $response = $this->post('api/v1/auth/refresh', [
            'refresh_token' => $refresh . 'a'
        ]);

        $response->assertStatus(500);
    }
}
