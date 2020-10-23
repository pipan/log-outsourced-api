<?php

namespace Tests\Feature\Api\V1\Settings\DefaultRole;

use App\Domain\Role\RoleEntity;
use App\Domain\Settings\DefaultRole\DefaultRoleEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class DefaultRoleLoadTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function testResponseOkEmpty()
    {
        $this->defaultRoleRepository->getMocker()
            ->getSimulation('get')
            ->whenInputReturn([], [1]);
        $response = $this->get('api/v1/settings/defaultroles?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => []
        ]);
    }

    public function testResponseOk()
    {
        $defaultRole = new DefaultRoleEntity([
            'id' => 1,
            'project_id' => 1,
            'role_id' => 1,
            'role' => new RoleEntity([
                'id' => 1,
                'uuid' => 'aabb',
                'project_id' => 1,
                'name' => 'Product.Access'
            ])
        ]);
        $this->defaultRoleRepository->getMocker()
            ->getSimulation('get')
            ->whenInputReturn([$defaultRole], [1]);

        $response = $this->get('api/v1/settings/defaultroles?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => ['Product.Access']
        ]);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->get('api/v1/settings/defaultroles?project_uuid=aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
