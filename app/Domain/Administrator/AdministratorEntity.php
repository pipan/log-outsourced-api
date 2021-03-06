<?php

namespace App\Domain\Administrator;

use App\Domain\IdEntity;
use Exception;
use Illuminate\Support\Facades\Hash;

class AdministratorEntity extends IdEntity
{
    public function __construct($data)
    {
        $passwordHash = $data['password_hash'] ?? '';
        if ($passwordHash === '' && isset($data['password'])) {
            $passwordHash = Hash::make($data['password'] ?? '');
        }

        parent::__construct([
            'id' => $data['id'] ?? 0,
            'uuid' => $data['uuid'] ?? '',
            'username' => $data['username'] ?? '',
            'password_hash' => $passwordHash,
            'invite_token' => $data['invite_token'] ?? ''
        ]);

        $validator = AdministratorValidator::forSchema($this->getId())->forEntity($this);
        if ($validator->fails()) {
            throw new Exception("Administrator entity is incorrect: " . $this->getUuid());
        }
    }

    protected function create($data)
    {
        return new AdministratorEntity($data);
    }

    public function getUuid()
    {
        return $this->data['uuid'];
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

    public function withPassword($value)
    {
        return $this->withPasswordHash(Hash::make($value));
    }

    public function withPasswordHash($value)
    {
        return $this->with('password_hash', $value)
            ->with('invite_token', '');
    }
}