<?php

namespace Tests\Feature\Api\V1\Settings\ProjectKey;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectKeyUpdateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectKeyTestSeeder::seed($this->projectKeyRepository);
    }

    public function getInvalidRequests()
    {
        return Requests::getInvalidForUpdates();
    }

    public function testResponseOk()
    {
        $response = $this->put('api/v1/settings/projectkeys/aabb', [
            'name' => 'prod',
            'key' => 'xxxx'
        ], AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'prod',
            'key' => 'aabb'
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/settings/projectkeys/0011', [
            'name' => 'prod'
        ], AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->put('api/v1/settings/projectkeys/aabb', $requestData, AuthHeaders::authorize());

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors', 'message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->put('api/v1/settings/projectkeys/aabb', [
            'name' => 'prod'
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
