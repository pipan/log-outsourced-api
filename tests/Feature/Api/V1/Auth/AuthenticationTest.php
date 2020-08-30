<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Administrator\AdministratorEntity;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class AuthenticationTest extends ControllerActionTestCase
{
    private function createUser($id, $username, $password)
    {
        return AdministratorEntity::createWithPassword($id, $username, $password);
    }

    public function testResponseOk()
    {
        $administrator = $this->createUser('1', 'test', 'test');

        $this->administratorRepository->getMocker()
            ->getSimulation('getByUsername')
            ->whenInputReturn($administrator, ['test']);

        $response = $this->post('api/v1/auth', [
            'username' => 'test',
            'password' => 'test'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'jwt' => 'testJWT'
        ]);
    }

    public function testResponseUnauthorizedIfUsernameNotFound()
    {
        $this->administratorRepository->getMocker()
            ->getSimulation('getByUsername')
            ->whenInputReturn(null, ['test']);

        $response = $this->post('api/v1/auth', [
            'username' => 'test',
            'password' => 'test'
        ]);

        $response->assertStatus(401);
    }

    public function testResponseUnauthorizedIfWrongPassword()
    {
        $administrator = $this->createUser('1', 'test', 'test');
        $this->administratorRepository->getMocker()
            ->getSimulation('getByUsername')
            ->whenInputReturn($administrator, ['test']);

        $response = $this->post('api/v1/auth', [
            'username' => 'test',
            'password' => 'aaaa'
        ]);

        $response->assertStatus(401);
    }
}
