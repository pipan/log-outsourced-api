<?php

namespace App\Repository\Database\Administrator;

use Lib\Adapter\NullAdapter;

class WriteAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return [
            'id' => $item->getId(),
            'username' => $item->getUsername(),
            'password_hash' => $item->getPasswordHash(),
            'invite_token' => $item->getInviteToken()
        ];
    }
}