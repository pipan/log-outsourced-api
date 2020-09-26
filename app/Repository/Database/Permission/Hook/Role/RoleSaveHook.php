<?php

namespace App\Repository\Database\Permission\Hook\Role;

use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class RoleSaveHook implements ActionHook
{
    public function onAction($role)
    {
        DB::table('permissions_roles')
            ->where('role_id', '=', $role->getId())
            ->delete();

        $bindings = [];
        foreach ($role->getPermissions() as $permission) {
            $result = DB::table('permissions')
                ->where('name', '=', $permission)
                ->where('project_id', '=', $role->getProjectId())
                ->first();
            if ($result === null) {
                $permissionId = DB::table('permissions')
                    ->insertGetId([
                        'name' => $permission,
                        'project_id' => $role->getProjectId()
                    ]);
            } else {
                $permissionId = $result->id;
            }
            $bindings[] = [
                'role_id' => $role->getId(),
                'permission_id' => $permissionId
            ];

        }
        DB::table('permissions_roles')
            ->insert($bindings);

        return $role;
    }
}