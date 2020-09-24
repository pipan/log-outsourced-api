<?php

namespace App\Repository\Database;

use Illuminate\Support\Facades\DB;

class SimpleDatabaseRepository
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function getAll()
    {
        return DB::table($this->table)->get();
    }

    public function get($id)
    {
        return DB::table($this->table)
            ->where('id', '=', $id)
            ->first();
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