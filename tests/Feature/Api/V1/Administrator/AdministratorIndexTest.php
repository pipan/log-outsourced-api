<?php

namespace Tests\Feature\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class AdministratorIndexTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([], [[]]);
        $response = $this->get('api/v1/administrators', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseNotEmpty()
    {
        $admin = new AdministratorEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'username' => 'admin',
            'password' => 'admin'
        ]);
        $this->administratorRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([$admin], [[]]);
        
        $response = $this->get('api/v1/administrators', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            [
                'uuid' => 'aabb',
                'username' => 'admin',
                'invite_token' => ''
            ]
        ]);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->get('api/v1/administrators');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
