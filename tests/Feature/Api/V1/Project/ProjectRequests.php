<?php

namespace Tests\Feature\Api\V1\Project;

class ProjectRequests
{
    public static function getInvalidForCreation()
    {
        $nameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $nameLong .= "a";
        }
        return [
            'name missing' => [
                []
            ],
            'name empty' => [
                [
                    'name' => ''
                ]
            ],
            'name too long' => [
                [
                    'name' => $nameLong
                ]
            ]
        ];
    }

    public static function getInvalidForUpdates()
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
            ]
        ];
    }
}