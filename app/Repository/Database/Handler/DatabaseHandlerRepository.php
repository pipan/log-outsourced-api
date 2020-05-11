<?php

namespace App\Repository\Database\Handler;

use App\Domain\Handler\HandlerRepository;
use Illuminate\Support\Facades\DB;
use Lib\Adapter\AdapterHelper;

class DatabaseHandlerRepository implements HandlerRepository
{
    const TABLE_NAME = 'handlers';

    private $readAdapter;

    public function __construct()
    {
        $this->readAdapter = new HandlerDatabaseReadAdapter();
    }

    public function getAll()
    {
        $result = DB::table(self::TABLE_NAME)->get();
        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt($result);
    }

    public function get($id)
    {
        $result = DB::table(self::TABLE_NAME)
            ->where('id', '=', $id)
            ->first();
        return $this->readAdapter->adapt($result);
    }

    public function getBySlug($slug)
    {
        $result = DB::table(self::TABLE_NAME)
            ->where('slug', '=', $slug)
            ->first();
        return $this->readAdapter->adapt($result);
    }
}