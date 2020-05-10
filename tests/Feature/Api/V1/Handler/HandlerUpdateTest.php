<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Handler\HandlerEntity;
use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Repository\HandlerRepositoryMock;
use Tests\Repository\ProjectRepositoryMock;
use Tests\TestCase;

class HandlerUpdateTest extends TestCase
{
    /**
     * @var HandlerRepositoryMock
     */
    private $handlerRepoMock;
    private $projectRepoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->handlerRepoMock = new HandlerRepositoryMock();
        $this->projectRepoMock = new ProjectRepositoryMock([]);
        $this->app->instance(Repository::class, new SimpleRepository(
            $this->projectRepoMock,
            $this->handlerRepoMock
        ));

        $this->projectRepoMock->createProject(1, 'aabb', 'project_name');
    }

    public function testResponseOk()
    {
        $this->handlerRepoMock->withEntity(
            new HandlerEntity(1, '0011', 1, 'handler_name')
        );
        $response = $this->put('api/v1/handlers/0011', [
            'name' => 'test_handler',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'test_handler'
        ]);
        $this->assertEquals('test_handler', $this->handlerRepoMock->getSaved()->getName());
    }

    public function testHandlerNotFound()
    {
        $response = $this->put('api/v1/handlers/0011', [
            'name' => 'test_handler',
        ]);

        $response->assertStatus(404);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $this->handlerRepoMock->withEntity(
            new HandlerEntity(1, '0011', 1, 'handler_name')
        );
        $response = $this->put('api/v1/handlers/0011', [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $this->handlerRepoMock->withEntity(
            new HandlerEntity(1, '0011', 1, 'handler_name')
        );
        $name = "";
        for ($i = 0; $i < 256; $i++) {
            $name .= "a";
        }
        $response = $this->put('api/v1/handlers/0011', [
            'name' => $name
        ]);

        $response->assertStatus(422);
    }
}
