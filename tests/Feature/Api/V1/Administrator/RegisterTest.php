<?php

namespace Tests\Feature\Api\V1\Administrator;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class RegisterTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AdministratorTestSeeder::seed($this->administratorRepository);
    }

    public function getInvalidRequests()
    {
        return RegisterRequests::getAllInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/register', [
            'invite_token' => '01234',
            'password' => 'test'
        ]);

        $response->assertStatus(200);
    }

    public function testOkUpdatePasswordHash()
    {
        $this->post('api/v1/register', [
            'invite_token' => '01234',
            'password' => 'test'
        ]);

        $result = $this->administratorRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $this->assertCount(1, $result);
        $this->assertNotEmpty($result[0][1]->getPasswordHash());
    }

    public function testOkUpdateInviteToken()
    {
        $this->post('api/v1/register', [
            'invite_token' => '01234',
            'password' => 'test'
        ]);

        $result = $this->administratorRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $this->assertCount(1, $result);
        $this->assertEmpty($result[0][1]->getInviteToken());
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('api/v1/register', $requestData);

        $response->assertStatus(422);
    }
}
