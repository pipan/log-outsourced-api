<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectUpdateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function getInvalidRequests()
    {
        return ProjectRequests::getInvalidForUpdates();
    }

    public function testResponseOk()
    {
        $response = $this->put('api/v1/projects/aabb', [
            'name' => 'new_project'
        ], AuthHeaders::authorize());

        $updated = $this->projectRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $response->assertStatus(200);
        $this->assertCount(1, $updated);
        $response->assertJsonFragment([
            'name' => 'new_project'
        ]);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->put(
            'api/v1/projects/aabb',
            $requestData,
            AuthHeaders::authorize()
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }
       

    public function testResponseNotFound()
    {
        $response = $this->put(
            'api/v1/projects/xxxx',
            [],
            AuthHeaders::authorize()
        );

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->put('api/v1/projects/aabb', [
            'name' => 'new_project'
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
