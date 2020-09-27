<?php

namespace Tests\Feature\Api\V1\Administrator;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class InviteCreateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AdministratorTestSeeder::seed($this->administratorRepository);
    }

    public function getInvalidRequests()
    {
        return InviteRequests::getAllInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/administrators/invite', [
            'username' => 'name@example.com'
        ], AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'username' => 'name@example.com',
        ]);
        $this->assertNotEmpty($response['invite_token']);

        $response->assertJsonStructure([
            'username', 'invite_token'
        ]);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->post('api/v1/administrators/invite', [
            'username' => 'name@example.com'
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('api/v1/administrators/invite', $requestData, AuthHeaders::authorize());

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }
}
