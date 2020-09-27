<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\Administrator\AdministratorTestSeeder;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AdministratorTestSeeder::seed($this->administratorRepository);
    }

    public function testResponseOk()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([]);
        $response = $this->get('api/v1/projects', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseNotEmpty()
    {
        $project = new ProjectEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'name' => 'test'
        ]);
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([$project]);
        
        $response = $this->get('api/v1/projects', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            [
                'uuid' => 'aabb',
                'name' => 'test'
            ]
        ]);
    }

    public function testResponseUnauthorized()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([]);
        $response = $this->get('api/v1/projects');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
