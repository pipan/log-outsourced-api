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

        $this->projectRepoMock = new ProjectRepositoryMock();
        $this->app->instance(Repository::class, new SimpleRepository(
            $this->projectRepoMock,
            null
        ));
    }

    public function testResponseOk()
    {
        $response = $this->get('api/v1/projects');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseNotEmpty()
    {

        $this->projectRepoMock->withAll([
            new ProjectEntity(1, hex2bin('aabc'), 'test')
        ]);
        
        $response = $this->get('api/v1/projects');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'uuid' => 'aabc',
                'name' => 'test'
            ]
        ]);
    }
}
