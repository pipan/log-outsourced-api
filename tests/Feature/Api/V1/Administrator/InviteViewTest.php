<?php

namespace Tests\Feature\Api\V1\Administrator;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class InviteViewTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AdministratorTestSeeder::seed($this->administratorRepository);
    }

    public function testResponseOk()
    {
        $response = $this->get('api/v1/administrators/invite/01234');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'username' => 'user',
        ]);

        $response->assertJsonStructure([
            'username', 'invite_token'
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('api/v1/administrators/invite/xxxx');

        $response->assertStatus(404);
    }

    public function testResponseMethodNotAllowdIfEmptyToken()
    {
        $response = $this->get('api/v1/administrators/invite');

        $response->assertStatus(405);
    }
}
