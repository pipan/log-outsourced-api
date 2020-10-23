<?php

namespace Tests\Feature\Api\V1\Settings\ProjectKey;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class ProjectKeyCreateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function getInvalidRequests()
    {
        return Requests::forCreation();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/settings/projectkeys', [
            'project_uuid' => 'aabb',
            'name' => 'Development'
        ], AuthHeaders::authorize());

        $saved = $this->projectKeyRepository->getMocker()
            ->getSimulation('insert')
            ->getExecutions();

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'key', 'name'
        ]);
        $response->assertJsonFragment([
            'name' => 'Development'
        ]);
        $this->assertCount(1, $saved);
        $this->assertEquals('Development', $saved[0][0]->getName());
        $this->assertEquals(1, $saved[0][0]->getProjectId());
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('api/v1/settings/projectkeys', $requestData, AuthHeaders::authorize());

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors', 'message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->post('api/v1/settings/projectkeys');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
