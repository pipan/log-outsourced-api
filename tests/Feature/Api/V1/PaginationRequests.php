<?php

namespace Tests\Feature\Api\V1;

class PaginationRequests
{
    public static function getPaginated($url, $params = [])
    {
        $query = [];
        foreach ($params as $key => $value) {
            $query[] = $key . "=" . urlencode($value);
        }
        $url .= "?";
        if (count($query) > 0) {
            $url .= implode("&", $query) . "&";
        }
        return [
            'no input' => [
                $url,
                ['limit' => 25, 'page' => 1, 'search_value' => '']
            ],
            'second page' => [
                $url . 'page=2',
                ['limit' => 25, 'page' => 2, 'search_value' => '']
            ],
            'negative page' => [
                $url . 'page=-1',
                ['limit' => 25, 'page' => 1, 'search_value' => '']
            ],
            'page zero' => [
                $url . 'page=0',
                ['limit' => 25, 'page' => 1, 'search_value' => '']
            ],
            'limit hundred' => [
                $url . 'limit=100',
                ['limit' => 100, 'page' => 1, 'search_value' => '']
            ],
            'limit negative' => [
                $url . 'limit=-100',
                ['limit' => 25, 'page' => 1, 'search_value' => '']
            ],
            'limit zero' => [
                $url . 'limit=0',
                ['limit' => 25, 'page' => 1, 'search_value' => '']
            ],
            'search_value' => [
                $url . 'search=aaaa',
                ['limit' => 25, 'page' => 1, 'search_value' => 'aaaa']
            ],
            'combination of all' => [
                $url . 'limit=10&page=5&search=aaaa',
                ['limit' => 10, 'page' => 5, 'search_value' => 'aaaa']
            ]
        ];
    }
}