<?php

namespace Tests\Feature\Api\V1\Administrator;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class LoginTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AdministratorTestSeeder::seed($this->administratorRepository);
    }

    public function getUnauthorizedRequests()
    {
        return LoginRequests::getAllInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/login', [
            'username' => 'root',
            'password' => 'root'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'jwt' => 'testJWT'
        ]);
    }

    /**
     * @dataProvider getUnauthorizedRequests
     */
    public function testResponseUnauthorized($requestData)
    {
        $response = $this->post('api/v1/login', $requestData);

        $response->assertStatus(401);
    }    
}
