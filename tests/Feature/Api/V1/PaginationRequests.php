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
                ['limit' => 25, 'page' => 1, 'max' => 1, 'total' => 20]
            ],
            'second page' => [
                $url . 'limit=10&page=2',
                ['limit' => 10, 'page' => 2, 'max' => 2, 'total' => 20]
            ],
            'negative page' => [
                $url . 'page=-1',
                ['limit' => 25, 'page' => 1, 'max' => 1, 'total' => 20]
            ],
            'page zero' => [
                $url . 'page=0',
                ['limit' => 25, 'page' => 1, 'max' => 1, 'total' => 20]
            ],
            'limit hundred' => [
                $url . 'limit=100',
                ['limit' => 100, 'page' => 1, 'max' => 1, 'total' => 20]
            ],
            'limit negative' => [
                $url . 'limit=-100',
                ['limit' => 25, 'page' => 1, 'max' => 1, 'total' => 20]
            ],
            'limit zero' => [
                $url . 'limit=0',
                ['limit' => 25, 'page' => 1, 'max' => 1, 'total' => 20]
            ],
            'limit too big' => [
                $url . 'limit=301',
                ['limit' => 300, 'page' => 1, 'max' => 1, 'total' => 20]
            ],
            'total remainder' => [
                $url . 'page=3&limit=3',
                ['limit' => 3, 'page' => 3, 'max' => 7, 'total' => 20]
            ],
            'page greater then max' => [
                $url . 'page=5&limit=15',
                ['limit' => 15, 'page' => 5, 'max' => 2, 'total' => 20]
            ],
        ];
    }
}