<?php

namespace App\Domain\Administrator;

use Illuminate\Support\Facades\Hash;
use Lib\Entity\Entity;

class AdministratorEntity extends Entity
{
    public function __construct($data)
    {
        $passwordHash = $data['password_hash'] ?? '';
        if ($passwordHash === '' && isset($data['password'])) {
            $passwordHash = Hash::make($data['password'] ?? '');
        }

        parent::__construct([
            'id' => $data['id'] ?? 0,
            'username' => $data['username'] ?? '',
            'password_hash' => $passwordHash,
            'invite_token' => $data['invite_token'] ?? ''
        ]);
    }

    /**
     * @deprecated
     */
    public static function createInvite($id, $username, $invoteToken)
    {
        return new AdministratorEntity([
            'id' => $id,
            'username' => $username,
            'invite_token' => $invoteToken
        ]);
    }

    /**
     * @deprecated
     */
    public static function createWithPassword($id, $username, $password)
    {
        return new AdministratorEntity([
            'id' => $id,
            'username' => $username,
            'password' => $password
        ]);
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getUsername()
    {
        return $this->data['username'];
    }

    public function getPasswordHash()
    {
        return $this->data['password_hash'];
    }

    public function getInviteToken()
    {
        return $this->data['invite_token'];
    }
}