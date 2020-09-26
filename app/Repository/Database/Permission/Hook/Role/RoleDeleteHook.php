<?php

namespace App\Repository\Database\Permission\Hook\Role;

use Illuminate\Support\Facades\DB;
use Lib\Hook\ActionHook;

class RoleDeleteHook implements ActionHook
{
    public function onAction($role)
    {
        DB::table('permissions_roles')
            ->where('role_id', '=', $role->getId())
            ->delete();
        
        return $role;
    }
}