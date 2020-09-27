<?php

namespace Tests\Feature\Api\V1\Administrator;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class AuthAccessTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AdministratorTestSeeder::seed($this->administratorRepository);
    }

    public function getUnauthorizedRequests()
    {
        return LoginRequests::getInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/access', [
            'username' => 'admin',
            'password' => 'admin'
        ]);

        $response->assertStatus(200);
        $response->assertCookieNotExpired('access');
        $response->assertCookieNotExpired('refresh');
    }

    public function testResponseOkUseRootUser()
    {
        $response = $this->post('api/v1/access', [
            'username' => 'root',
            'password' => 'root'
        ]);

        $response->assertStatus(200);
        $response->assertCookieNotExpired('access');
        $response->assertCookieNotExpired('refresh');
    }

    /**
     * @dataProvider getUnauthorizedRequests
     */
    public function testResponseUnauthorized($requestData)
    {
        $response = $this->post('api/v1/access', $requestData);

        $response->assertStatus(401);
    }
}
