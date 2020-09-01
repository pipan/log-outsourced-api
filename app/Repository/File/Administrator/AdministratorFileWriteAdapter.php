<?php

namespace App\Repository\File\Administrator;

use Lib\Adapter\Adapter;

class AdministratorFileWriteAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'id' => $item->getId(),
            'username' => $item->getUsername(),
            'password_hash' => $item->getPasswordHash()
        ];
    }
}