<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Administrator\AdministratorEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class InviteTest extends ControllerActionTestCase
{
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
        $administrator = $this->createUser('1', 'test', 'test');
        $this->administratorRepository->getMocker()
            ->getSimulation('getByUsername')
            ->whenInputReturn($administrator, ['test']);

        $response = $this->post('api/v1/invite', [
            'username' => 'test'
        ]);

        $response->assertStatus(422);
    }
}
