<?php

namespace Tests\Feature\Api\V1\Project;

use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Repository\ProjectRepositoryMock;
use Tests\TestCase;

class ProjectIndexTest extends TestCase
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
        $response = $this->get('api/v1/projects');

        $response->assertStatus(200);
    }

    public function testResponseEmpty()
    {
        $response = $this->get('api/v1/projects');

        $response->assertJson([]);
    }

    public function testResponseNotEmpty()
    {
        $this->projectRepoMock->createProject(1, 'aabc', 'test');
        
        $response = $this->get('api/v1/projects');

        $response->assertJson([
            [
                'uuid' => 'aabc',
                'name' => 'test'
            ]
        ]);
    }
}
