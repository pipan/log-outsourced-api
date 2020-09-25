<?php

namespace Tests\Feature\Api\V1\Listener;

class ListenerRequests
{
    public static function getInvalidForCreate()
    {
        $nameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $nameLong .= "a";
        }

        return [
            'name missing' => [
                [
                    'project_uuid' => 'aabb',
                    'handler_slug' => 'file',
                    'rules' => []
                ]
            ],
            'name empty' => [
                [
                    'name' => '',
                    'project_uuid' => 'aabb',
                    'handler_slug' => 'file',
                    'rules' => []
                ]
            ],
            'name too long' => [
                [
                    'name' => $nameLong,
                    'project_uuid' => 'aabb',
                    'handler_slug' => 'file',
                    'rules' => []
                ]
            ],
            'project uuid missing' => [
                [
                    'name' => 'test_handler',
                    'handler_slug' => 'file',
                    'rules' => []
                ]
            ],
            'project uuid not found' => [
                [
                    'name' => 'test_handler',
                    'project_uuid' => '0011',
                    'handler_slug' => 'file',
                    'rules' => []
                ]
            ],
            'slug missing' => [
                [
                    'name' => 'test_handler',
                    'project_uuid' => 'aabb',
                    'rules' => []
                ]
            ],
            'handler not found by slug' => [
                [
                    'name' => 'test_handler',
                    'project_uuid' => 'aabb',
                    'handler_slug' => 'non-existing',
                    'rules' => []
                ]
            ],
            'rules as string' => [
                [
                    'name' => 'test_handler',
                    'project_uuid' => 'aabb',
                    'handler_slug' => 'file',
                    'rules' => '[]'
                ]
            ],
            'rules as number' => [
                [
                    'name' => 'test_handler',
                    'project_uuid' => 'aabb',
                    'handler_slug' => 'file',
                    'rules' => 0
                ]
            ],
            'rules as empty string' => [
                [
                    'name' => 'test_handler',
                    'project_uuid' => 'aabb',
                    'handler_slug' => 'file',
                    'rules' => ''
                ]
            ]
        ];
    }

    public static function getAllInvalid()
    {
        $nameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $nameLong .= "a";
        }

        return [
            'name empty' => [
                [
                    'name' => ''
                ]
            ],
            'name too long' => [
                [
                    'name' => $nameLong
                ]
            ],
            'rules as string' => [
                [
                    'rules' => 'error',
                ]
            ],
            'rules as number' => [
                [
                    'rules' => 1
                ]
            ],
            'rules as empty string' => [
                [
                    'rules' => ''
                ]
            ],
            'handler slug not exists' => [
                [
                    'handler_slug' => 'non-existing'
                ]
            ]
        ];
    }
}