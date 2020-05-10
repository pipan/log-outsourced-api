<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Handler\HandlerEntity;
use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Tests\Repository\HandlerRepositoryMock;
use Tests\Repository\ProjectRepositoryMock;
use Tests\TestCase;

class HandlerDeleteTest extends TestCase
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
    }

    public function testResponseOk()
    {
        $this->handlerRepoMock->withEntity(
            new HandlerEntity(1, hex2bin('0011'), 1, 'test_handler')
        );
        $response = $this->delete('api/v1/handlers/0011');

        $response->assertStatus(200);
        $this->assertEquals('test_handler', $this->handlerRepoMock->getDeleted()->getName());
    }

    public function testHandlerNotFound()
    {
        $response = $this->delete('api/v1/handlers/0011', [
            'name' => 'test_handler',
        ]);

        $response->assertStatus(404);
    }
}
