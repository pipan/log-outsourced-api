<?php

namespace App\Repository\Database;

use Illuminate\Support\Facades\DB;
use Lib\Adapter\Adapter;

abstract class UuidDatabaseRepository
{
    protected $table;
    protected $resultAdapter;

    public function __construct($table, Adapter $resultAdapter)
    {
        $this->table = $table;
        $this->resultAdapter = $resultAdapter;
    }

    public function getByUuid($uuid)
    {
        $result = DB::table($this->table)
            ->where('uuid', '=', $uuid)
            ->first();

        return $this->resultAdapter->adapt($result);
    }

    public function getByHexUuid($hexUuid)
    {
        return $this->getByUuid(hex2bin($hexUuid));
    }
}