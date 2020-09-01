<?php

namespace Tests\Feature\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class InviteTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();


        $administrators = [
            $this->createUser('1', 'root', 'root')
        ];
        foreach ($administrators as $administrator) {
            $this->administratorRepository->getMocker()
                ->getSimulation('getByUsername')
                ->whenInputReturn($administrator, [$administrator->getUsername()]);
            $this->administratorRepository->getMocker()
                ->getSimulation('exists')
                ->whenInputReturn(true, [$administrator->getUsername()]);
        }
    }

    private function createUser($id, $username, $password)
    {
        return AdministratorEntity::createWithPassword($id, $username, $password);
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/invite', [
            'username' => 'test'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'username' => 'test',
        ]);

        $response->assertJsonStructure([
            'id', 'username', 'invite_token'
        ]);
    }

    public function testResponseValidationErrorIfEmptyUsesrname()
    {
        $response = $this->post('api/v1/invite', [
            'username' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfUsernameTooLong()
    {
        $username = "";
        for ($i = 0; $i < 256; $i++) {
            $username .= "a";
        }
        $response = $this->post('api/v1/invite', [
            'username' => $username
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfUsernameExists()
    {
        $response = $this->post('api/v1/invite', [
            'username' => 'root'
        ]);

        $response->assertStatus(422);
    }
}
