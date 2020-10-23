<?php

namespace App\Repository\Database\Settings\DefaultRole;

use App\Domain\Settings\DefaultRole\DefaultRoleRepository;
use App\Repository\Database\AdapterDatabaseIo;
use App\Repository\Database\HookDatabaseIo;
use App\Repository\Database\Settings\DefaultRole\Hook\Role\RoleLoadHook;
use App\Repository\Database\SimpleDatabaseIo;
use Illuminate\Support\Facades\DB;
use Lib\Entity\EntityBlacklistAdapter;

class DefaultRoleDatabaseRepository implements DefaultRoleRepository
{
    const TABLE = 'default_roles';

    private $io;

    public function __construct()
    {
        $this->io = new HookDatabaseIo(
            new AdapterDatabaseIo(
                new SimpleDatabaseIo(self::TABLE),
                new ReadAdapter(),
                new EntityBlacklistAdapter(['id', 'role'])
            )
        );

        $this->io->addHook('load', new RoleLoadHook());
    }

    public function get($projectId)
    {
        $result = DB::table(self::TABLE)
            ->where('project_id', '=', $projectId)
            ->get();

        return $this->io->selectList($result);
    }

    public function set($projectId, $roles)
    {
        $defaultRoles = [];
        foreach ($roles as $role) {
            $defaultRoles[] = [
                'project_id' => $projectId,
                'role_id' => $role
            ];
        }

        DB::table(self::TABLE)
            ->where('project_id', '=', $projectId)
            ->delete();

        DB::table(self::TABLE)
            ->insert($defaultRoles);
    }
}