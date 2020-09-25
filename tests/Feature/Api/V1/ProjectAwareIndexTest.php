<?php

namespace Tests\Feature\Api\V1;

use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class ProjectAwareIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function getAllUrls()
    {
        return [
            'Users' => ['api/v1/users'],
            'Roles' => ['api/v1/roles'],
            'Listeners' => ['api/v1/listeners']
        ];
    }

    /**
     * @dataProvider getAllUrls
     */
    public function testResponseNotFoundIfProjectNotExists($url)
    {
        $response = $this->get($url . '?project_uuid=9988');
        $response->assertStatus(404);
    }

    /**
     * @dataProvider getAllUrls
     */
    public function testResponseNotFoundIfMissingProjectUuid($url)
    {
        $response = $this->get($url);
        $response->assertStatus(404);
    }

    /**
     * @dataProvider getAllUrls
     */
    public function testResponseNotFoundIfEmptyProjectUuid($url)
    {
        $response = $this->get($url . '?project_uuid');
        $response->assertStatus(404);
    }
}
