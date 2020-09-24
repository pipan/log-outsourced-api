<?php

namespace App\Repository\Database\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use App\Domain\Administrator\AdministratorRepository;
use App\Repository\Database\AdapterDatabaseRepository;
use Illuminate\Support\Facades\DB;

class AdministratorDatabaseRepository implements AdministratorRepository
{
    const TABLE = 'administrators';

    private $readAdapter;
    private $writeAdapter;
    private $adapterRepository;

    public function __construct()
    {
        $this->readAdapter = new ReadAdapter();
        $this->writeAdapter = new WriteAdapter();
        $this->adapterRepository = new AdapterDatabaseRepository(self::TABLE, $this->readAdapter, $this->writeAdapter);
    }

    public function getByUsername($username): ?AdministratorEntity
    {
        $result = DB::table(self::TABLE)
            ->where('username', '=', $username)
            ->first();

        return $this->readAdapter->adapt($result);
    }

    public function getByInviteToken($token): ?AdministratorEntity
    {
        $result = DB::table(self::TABLE)
            ->where('invite_token', '=', $token)
            ->first();

        return $this->readAdapter->adapt($result);
    }

    public function get($id): ?AdministratorEntity
    {
        return $this->adapterRepository->get($id);
    }

    public function insert(AdministratorEntity $entity): AdministratorEntity
    {
        $id = $this->adapterRepository->insert($entity);
        return $this->adapterRepository->get($id);
    }

    public function update($id, AdministratorEntity $entity): AdministratorEntity
    {
        $this->adapterRepository->update($id, $entity);
        return $this->adapterRepository->get($id);
    }

    public function delete(AdministratorEntity $entity)
    {
        $this->simpleRepository->delete($entity->getId());
    }
}