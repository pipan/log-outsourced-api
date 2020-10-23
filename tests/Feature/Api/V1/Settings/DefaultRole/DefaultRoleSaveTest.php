<?php

namespace Tests\Feature\Api\V1\Settings\DefaultRole;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class DefaultRoleSaveTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function getInvalidRequests()
    {
        return Requests::getInvalidForSave();
    }

    public function testResponseOk()
    {
        $role = new RoleEntity([
            'id' => 100,
            'uuid' => 'aabb',
            'project_id' => 1,
            'name' => 'Product.Access'
        ]);
        $this->defaultRoleRepository->getMocker()
            ->getSimulation('get')
            ->whenInputReturn([], [1]);
        $this->roleRepository->getMocker()
            ->getSimulation('findListForProjectByNames')
            ->whenInputReturn([$role], [1, ['Product.Access']]);

        $response = $this->post('api/v1/settings/defaultroles', [
            'project_uuid' => 'aabb',
            'roles' => ['Product.Access']
        ], AuthHeaders::authorize());

        $save = $this->defaultRoleRepository->getMocker()
            ->getSimulation('set')
            ->getExecutions();

        $response->assertStatus(200);
        $this->assertCount(1, $save);
        $this->assertEquals([100], $save[0][1]);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseInvalid($requestData)
    {
        $response = $this->post('api/v1/settings/defaultroles', $requestData, AuthHeaders::authorize());

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->post('api/v1/settings/defaultroles', [
            'project_uuid' => 'aabb',
            'roles' => ['Project.Access']
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
