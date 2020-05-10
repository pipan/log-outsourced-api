<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Repository\ProjectRepositoryMock;
use Tests\TestCase;

class ProjectUpdateTest extends TestCase
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
        $response = $this->put('api/v1/projects/aabb', [
            'name' => 'new_project'
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $this->projectRepoMock->getSaved()->getId());
        $response->assertJsonFragment([
            'name' => 'new_project'
        ]);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $this->projectRepoMock->withEntity(
            new ProjectEntity(1, hex2bin('aabb'), 'test_project')
        );
        $response = $this->put('api/v1/projects/aabb', [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $this->projectRepoMock->withEntity(
            new ProjectEntity(1, hex2bin('aabb'), 'test_project')
        );
        $name = "";
        for ($i = 0; $i < 256; $i++) {
            $name .= "a";
        }
        
        $response = $this->put('api/v1/projects/aabb', [
            'name' => $name
        ]);

        $response->assertStatus(422);
    }
       

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/projects/aabb');

        $response->assertStatus(404);
    }
}
