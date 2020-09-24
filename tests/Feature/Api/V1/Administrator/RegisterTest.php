<?php

namespace Tests\Feature\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class RegisterTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $administrators = [
            $this->createUser('1', 'test', '', '010101'),
            $this->createUser('2', 'test-pass', 'aaaaaa', '')
        ];

        foreach ($administrators as $administrator) {
            $this->administratorRepository->getMocker()
                ->getSimulation('getByInviteToken')
                ->whenInputReturn($administrator, [$administrator->getInviteToken()]);
            $this->administratorRepository->getMocker()
                ->getSimulation('get')
                ->whenInputReturn($administrator, [$administrator->getId()]);
        }
    }

    private function createUser($id, $username, $password, $inviteToken)
    {
        return new AdministratorEntity($id, $username, $password, $inviteToken);
    }

    public function getInvalidRequests()
    {
        return RegisterRequests::getAllInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/register', [
            'invite_token' => '010101',
            'password' => 'test'
        ]);

        $response->assertStatus(200);
    }

    public function testOkUpdatePasswordHash()
    {
        $this->post('api/v1/register', [
            'invite_token' => '010101',
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
            'invite_token' => '010101',
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
