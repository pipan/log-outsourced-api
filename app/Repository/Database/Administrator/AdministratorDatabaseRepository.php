<?php

namespace App\Repository\Database\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use App\Domain\Administrator\AdministratorRepository;
use App\Domain\Administrator\AdministratorSchema;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\PaginationQuery;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;
use Lib\Pagination\PaginationEntity;

class AdministratorDatabaseRepository implements AdministratorRepository
{
    const TABLE = 'administrators';

    private $io;

    public function __construct()
    {
        $this->io = new HookDatabaseIo(
            new AdapterDatabaseIo(
                new SimpleDatabaseIo(self::TABLE),
                new ReadAdapter(),
                AdministratorSchema::forWriting()
            )
        );
    }

    public function getAll(PaginationEntity $entity)
    {
        $results = PaginationQuery::getExtensionForEntity($entity)
            ->extend(DB::table(self::TABLE))
            ->get();

        return $this->io->selectList($results);
    }

    public function countAll($search)
    {
        return PaginationQuery::getSearchExtension('username', $search)
            ->extend(DB::table(self::TABLE))
            ->count();
    }

    public function getByUuid($uuid): ?AdministratorEntity
    {
        $result = DB::table(self::TABLE)
            ->where('uuid', '=', $uuid)
            ->first();
        if ($result === null) {
            return null;
        }

        return $this->io->select($result);
    }

    public function getByUsername($username): ?AdministratorEntity
    {
        $result = DB::table(self::TABLE)
            ->where('username', '=', $username)
            ->first();

        return $this->io->select($result);
    }

    public function getByInviteToken($token): ?AdministratorEntity
    {
        $result = DB::table(self::TABLE)
            ->where('invite_token', '=', $token)
            ->first();

        return $this->io->select($result);
    }

    public function get($id): ?AdministratorEntity
    {
        return $this->io->find($id);
    }

    public function insert(AdministratorEntity $entity): AdministratorEntity
    {
        return $this->io->insert($entity);
    }

    public function update($id, AdministratorEntity $entity): AdministratorEntity
    {
        return $this->io->update($id, $entity);
    }

    public function delete(AdministratorEntity $entity)
    {
        $this->io->delete($entity->getId());
    }
}