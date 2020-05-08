<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
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

    private function createProject($hexUuid, $name)
    {
        return new ProjectEntity(hex2bin($hexUuid), $name);
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
        $this->projectRepoMock->setAll([$this->createProject('aabc', 'test')]);
        
        $response = $this->get('api/v1/projects');

        $response->assertJson([
            [
                'uuid' => 'aabc',
                'name' => 'test'
            ]
        ]);
    }
}
