<?php

namespace Tests\Feature\Api\V1\Permission;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;
use Tests\Feature\Api\V1\User\UserTestSeeder;

class PermissionValidateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
        UserTestSeeder::seed($this->userRepository);

        $role = new RoleEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'name' => 'user.access',
            'project_id' => 1,
            'permissions' => ['user.view']
        ]);
        $this->roleRepository->getMocker()
            ->getSimulation('getForUser')
            ->whenInputReturn([$role], [1]);
    }

    public function getInvalidRequests()
    {
        return PermissionRequests::getInvalidForValidation();
    }

    public function getNotFoundRequests()
    {
        return PermissionRequests::getNotFoundForValidation();
    }

    public function testResponseOk()
    {
        $response = $this->get('permissions/aabb?user=admin&permissions[]=user.view');

        $response->assertStatus(200);
        $response->assertJson([
            'permissions' => [
                'user.view'
            ]
        ]);
    }

    public function testResponseOkRequiredPermissionNotFound()
    {
        $response = $this->get('permissions/aabb?user=admin&permissions[]=user.create');

        $response->assertStatus(200);
        $response->assertJson([
            'permissions' => []
        ]);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseInvalidRequest($url)
    {
        $response = $this->get($url);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }

    /**
     * @dataProvider getNotFoundRequests
     */
    public function testResponseNotFound($url)
    {
        $response = $this->get($url);

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }
}