<?php

namespace App\Repository\Database\Permission\Hook\Role;

use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class RoleLoadHook implements ActionHook
{
    public function onAction($roles)
    {
        $ids = [];
        foreach ($roles as $role) {
            $ids[] = $role->getId();
        }

        $result = DB::table('permissions_roles')
            ->join('permissions', 'permissions_roles.permission_id', '=', 'permissions.id')
            ->whereIn('permissions_roles.role_id', $ids)
            ->get();

        $permissions = [];
        foreach ($result as $item) {
            if (!isset($permissions[$item->role_id])) {
                $permissions[$item->role_id] = [];
            }
            $permissions[$item->role_id][] = $item->name;
        }

        $newRoles = [];
        foreach ($roles as $role) {
            if (!isset($permissions[$role->getId()])) {
                $newRoles[] = $role;
                continue;
            }
            $newRoles[] = $role->withPermissions(
                $permissions[$role->getId()]
            );
        }

        return $newRoles;
    }
}