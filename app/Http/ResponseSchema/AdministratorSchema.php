<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\Adapter;

class AdministratorSchema implements Adapter
{
    public function adapt($item)
    {
        return [
            'username' => $item->getUsername(),
            'invite_token' => $item->getInviteToken()
        ];
    }
}