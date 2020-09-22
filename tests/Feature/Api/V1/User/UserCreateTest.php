<?php

namespace Tests\Feature\Api\V1\User;

use App\Domain\Project\ProjectEntity;
use App\Domain\User\UserEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class UserCreateController extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'test project'),
                ['aabb']
            );
        $this->projectRepository->getMocker()
            ->getSimulation('exists')
            ->whenInputReturn(true, ['aabb']);

        $this->userRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new UserEntity(1, 'aabb', 'test@example.com', 1, []),
                ['aabb']
            );
        $this->userRepository->getMocker()
            ->getSimulation('getByUsernameForProject')
            ->whenInputReturn(
                new UserEntity(1, 'aabb', 'test@example.com', 1, []),
                ['test@example.com', 1]
            );
    }

    public function getAllInvalidRequestData()
    {
        return UserRequests::getAllInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->post('/api/v1/users', [
            'project_uuid' => 'aabb',
            'username' => 'valid@example.com'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['uuid', 'username', 'roles']);
        $response->assertJsonFragment([
            'username' => 'valid@example.com',
            'roles' => []
        ]);
    }

    public function testResponseOkWithPermissions()
    {
        $response = $this->post('/api/v1/users', [
            'project_uuid' => 'aabb',
            'username' => 'valid@example.com',
            'roles' => [1]
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['uuid', 'username', 'roles']);
        $response->assertJsonFragment([
            'username' => 'valid@example.com',
            'roles' => [1]
        ]);
    }

    /**
     * @dataProvider getAllInvalidRequestData
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('/api/v1/users', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}