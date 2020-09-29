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

    public function testResponseOkEmpty()
    {
        $pagination = (new PaginationEntity([]))
            ->searchBy('username')
            ->orderBy('username');
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([], [$pagination]);
        $this->administratorRepository->getMocker()
            ->getSimulation('countAll')
            ->whenInputReturn(0, ['']);
        $response = $this->get('api/v1/administrators', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [],
            'meta' => [
                'pagination' => [
                    'page' => 1,
                    'limit' => 25,
                    'max' => 0
                ]
            ]
        ]);
    }

    public function testResponseOk()
    {
        $pagination = (new PaginationEntity([]))
            ->searchBy('username')
            ->orderBy('username');
        $admin = new AdministratorEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'username' => 'admin',
            'password' => 'admin'
        ]);
        $this->administratorRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([$admin], [$pagination]);
        $this->administratorRepository->getMocker()
            ->getSimulation('countAll')
            ->whenInputReturn(1, ['']);
        
        $response = $this->get('api/v1/administrators', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [[
                'uuid' => 'aabb',
                'username' => 'admin',
                'invite_token' => ''
            ]],
            'meta' => [
                'pagination' => [
                    'page' => 1,
                    'limit' => 25,
                    'max' => 1
                ]
            ]
        ]);
    }

    /**
     * @dataProvider getPaginatedRequests
     */
    public function testResponsePaginated($url, $pagination)
    {
        $this->administratorRepository->getMocker()
            ->getSimulation('countAll')
            ->whenInputReturn(20, ['']);
        
        $response = $this->get($url, AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [],
            'meta' => [
                'pagination' => $pagination
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
