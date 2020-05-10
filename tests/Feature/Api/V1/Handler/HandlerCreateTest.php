<?php

namespace Tests\Feature\Api\V1\Project;

use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Repository\HandlerRepositoryMock;
use Tests\Repository\ProjectRepositoryMock;
use Tests\TestCase;

class HandlerCreateTest extends TestCase
{
    private $handlerRepoMock;
    private $projectRepoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->handlerRepoMock = new HandlerRepositoryMock([]);
        $this->projectRepoMock = new ProjectRepositoryMock([]);
        $this->app->instance(Repository::class, new SimpleRepository(
            $this->projectRepoMock,
            $this->handlerRepoMock
        ));

        $this->projectRepoMock->createProject(1, 'aabb', 'project_name');
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/handlers', [
            'name' => 'test_handler',
            'project_uuid' => 'aabb'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'test_handler',
            'project_id' => 1
        ]);
        $this->assertEquals('test_handler', $this->handlerRepoMock->getSaved()->getName());
    }

    public function testResponseValidationErrorMissingName()
    {
        $response = $this->post('api/v1/handlers', [
            'project_uuid' => 'aabb'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $response = $this->post('api/v1/handlers', [
            'name' => '',
            'project_uuid' => 'aabb'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $name = "";
        for ($i = 0; $i < 256; $i++) {
            $name .= "a";
        }
        $response = $this->post('api/v1/handlers', [
            'name' => $name,
            'project_uuid' => 'aabb'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorProjectUuidMissing()
    {
        $response = $this->post('api/v1/handlers', [
            'name' => 'test_handler'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorWrongProjectUuid()
    {
        $response = $this->post('api/v1/handlers', [
            'name' => 'test_handler',
            'project_uuid' => 'aacc'
        ]);

        $response->assertStatus(422);
    }
}
