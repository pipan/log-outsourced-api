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

        $this->projectRepoMock->createProject(1, 'aabb', 'test_project');
    }

    public function testResponseOk()
    {
        $response = $this->get('api/v1/projects/aabb');

        $response->assertStatus(200);
    }

    public function testResponseNotFoundSomeProjects()
    {
        $response = $this->get('api/v1/projects/aacc');

        $response->assertStatus(404);
    }
}
