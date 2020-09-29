<?php

namespace Tests\Feature\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use Lib\Pagination\PaginationEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\PaginationRequests;

class AdministratorIndexTest extends ControllerActionTestCase
{
    public function getPaginatedRequests()
    {
        return PaginationRequests::getPaginated('api/v1/administrators');
    }

    public function testResponseOk()
    {
        $pagination = new PaginationEntity([]);
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([], [$pagination]);
        $response = $this->get('api/v1/administrators', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /**
     * @dataProvider getPaginatedRequests
     */
    public function testResponsePaginated($url, $paginationConfig)
    {
        $pagination = new PaginationEntity($paginationConfig);
        $admin = new AdministratorEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'username' => 'admin',
            'password' => 'admin'
        ]);
        $this->administratorRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([$admin], [$pagination]);
        
        $response = $this->get($url, AuthHeaders::authorize());

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
