<?php

namespace App\Repository\Database\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use App\Domain\Administrator\AdministratorRepository;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;
use Lib\Entity\EntityBlacklistAdapter;

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
                new EntityBlacklistAdapter(['id'])
            )
        );
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