<?php

namespace Tests\Feature\Api\V1\Role;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class RoleCreateTest extends ControllerActionTestCase
{
    public function getAllInvalidRequestData()
    {
        return RoleRequests::getAllInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/roles', [
            'domain' => 'Product',
            'name' => 'Access',
            'permissions' => ['product.view']
        ]);

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $response->assertJsonStructure([
            'uuid', 'domain', 'name', 'permissions'
        ]);
        $response->assertJsonFragment([
            'domain' => 'Product',
            'name' => 'Access',
            'permissions' => ['product.view']
        ]);
    }

    /**
     * @dataProvider getAllInvalidRequestData
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('api/v1/roles', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}
