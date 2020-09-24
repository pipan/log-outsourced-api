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
        $response = $this->post('api/v1/invite', [
            'username' => 'name@example.com'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'username' => 'name@example.com',
        ]);

        $response->assertJsonStructure([
            'username', 'invite_token'
        ]);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('api/v1/invite', $requestData);

        $response->assertStatus(422);
    }
}
