<?php

namespace App\Repository\Database\Settings\DefaultRole\Hook\Role;

use App\Domain\Role\RoleEntity;
use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class RoleLoadHook implements ActionHook
{
    public function onAction($defaultRoles)
    {
        $ids = [];
        foreach ($defaultRoles as $default) {
            $ids[] = $default->getRoleId();
        }

        $result = DB::table('roles')
            ->whereIn('id', $ids)
            ->get();

        $roles = [];
        foreach ($result as $item) {
            $roles[$item->id] = new RoleEntity((array) $item);
        }

        $newDefaultRoles = [];
        foreach ($defaultRoles as $default) {
            if (!isset($roles[$default->getRoleId()])) {
                $newDefaultRoles[] = $default;
                continue;
            }
            $newDefaultRoles[] = $default->withRole(
                $roles[$default->getRoleId()]
            );
        }

        return $newDefaultRoles;
    }
}