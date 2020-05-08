<?php

namespace Tests\Feature\Api\V1\Project;

use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Repository\ProjectRepositoryMock;
use Tests\TestCase;

class ProjectCreateTest extends TestCase
{
    private $projectRepoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->projectRepoMock = new ProjectRepositoryMock([]);
        $this->app->instance(Repository::class, new SimpleRepository(
            $this->projectRepoMock,
            null
        ));
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/projects', [
            'name' => 'test_project'
        ]);

        $response->assertStatus(201);
    }

    public function testResponseOkSavedProject()
    {
        $response = $this->post('api/v1/projects', [
            'name' => 'test_project'
        ]);

        $this->assertEquals('test_project', $this->projectRepoMock->getLastCreated()->getName());
    }

    public function testResponseValidationErrorMissingName()
    {
        $response = $this->post('api/v1/projects');

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $response = $this->post('api/v1/projects', [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $name = "";
        for ($i = 0; $i < 256; $i++) {
            $name .= "a";
        }
        $response = $this->post('api/v1/projects', [
            'name' => $name
        ]);

        $response->assertStatus(422);
    }
}
