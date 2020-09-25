<?php

namespace App\Repository\Database;

interface DatabaseIo
{
    public function select($result);
    public function selectList($results);
    public function find($id);
    public function findList($ids);

    public function insert($entity);
    public function update($id, $entity);
    public function delete($id);
}