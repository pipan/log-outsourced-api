<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Repository\ProjectRepositoryMock;
use Tests\TestCase;

class ProjectViewTest extends TestCase
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

    private function createProject($hexUuid, $name)
    {
        return new ProjectEntity(hex2bin($hexUuid), $name);
    }

    public function testResponseOk()
    {
        $this->projectRepoMock->setAll([
            $this->createProject('aabb', 'test_project')
        ]);

        $response = $this->get('api/v1/projects/aabb');

        $response->assertStatus(200);
    }

    public function testResponseNotFoundEmptyProjects()
    {
        $response = $this->get('api/v1/projects/aabb');

        $response->assertStatus(404);
    }

    public function testResponseNotFoundSomeProjects()
    {
        $this->projectRepoMock->setAll([
            $this->createProject('aacc', 'test_project')
        ]);

        $response = $this->get('api/v1/projects/aabb');

        $response->assertStatus(404);
    }
}
