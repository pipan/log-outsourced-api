<?php

namespace App\Repository\Database;

use Illuminate\Support\Facades\DB;

class SimpleDatabaseIo implements DatabaseIo
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function select($result)
    {
        return $result;
    }

    public function selectList($results)
    {
        return $results;
    }

    public function findList($ids)
    {
        return DB::table($this->table)
            ->whereIn('id', $ids)
            ->get();
    }

    public function find($id)
    {
        $entities = $this->findList([$id]);
        return $entities[0] ?? null;
    }

    public function insert($data)
    {
        return DB::table($this->table)
            ->insertGetId($data);
    }

    public function update($id, $data)
    {
        DB::table($this->table)
            ->where('id', '=', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return DB::table($this->table)
            ->where('id', '=', $id)
            ->delete();
    }
}