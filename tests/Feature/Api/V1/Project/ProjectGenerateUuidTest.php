<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Repository\ProjectRepositoryMock;
use Tests\TestCase;

class ProjectGenerateUuidTest extends TestCase
{
    private $projectRepoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->projectRepoMock = new ProjectRepositoryMock();
        $this->app->instance(Repository::class, new SimpleRepository(
            $this->projectRepoMock,
            null
        ));
    }

    public function testResponseOk()
    {
        $this->projectRepoMock->withEntity(
            new ProjectEntity(1, hex2bin('aabb'), 'test_project')
        );
        $response = $this->put('api/v1/projects/aabb/generate');

        $response->assertStatus(200);
        $this->assertEquals(1, $this->projectRepoMock->getSaved()->getId());
        $this->assertNotEquals('aabb', $response->json('uuid'));
    }

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/projects/aabb/generate');

        $response->assertStatus(404);
    }
}
