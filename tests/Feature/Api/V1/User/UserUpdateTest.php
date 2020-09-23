<?php

namespace Tests\Feature\Api\V1\User;

use App\Domain\User\UserEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class UserUpdateController extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

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
        return UserRequests::getRolesInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->put('/api/v1/users/aabb', [
            'roles' => [1]
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['uuid', 'username', 'roles']);
        $response->assertJsonFragment([
            'username' => 'test@example.com',
            'roles' => [1]
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->put('/api/v1/users/0123', [
            'roles' => [1]
        ]);

        $response->assertStatus(404);
    }

    /**
     * @dataProvider getAllInvalidRequestData
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->put('/api/v1/users/aabb', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}