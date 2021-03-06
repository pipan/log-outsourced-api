<?php

namespace Tests\Feature\Api\V1\Listener;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ListenerUpdateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ListenerTestSeeder::seed($this->listenerRepository);
    }

    public function provideInvalidRequests()
    {
        return ListenerRequests::getAllInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->put('api/v1/listeners/aabb', [
            'name' => 'test',
        ], AuthHeaders::authorize());

        $updated = $this->listenerRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'test'
        ]);
        $this->assertCount(1, $updated);
    }

    public function testResponseOkDuplicateRules()
    {
        $response = $this->put('api/v1/listeners/aabb', [
            'name' => 'test',
            'rules' => ['error', 'error']
        ], AuthHeaders::authorize());

        $updated = $this->listenerRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'rules' => ['error']
        ]);
        $this->assertCount(1, $updated);
    }

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/listeners/0011', [
            'name' => 'test_handler',
        ], AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @dataProvider provideInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->put(
            'api/v1/listeners/aabb',
            $requestData,
            AuthHeaders::authorize()
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors', 'message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->put('api/v1/listeners/aabb', [
            'name' => 'test_handler'
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
