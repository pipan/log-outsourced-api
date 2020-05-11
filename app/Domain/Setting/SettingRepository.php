<?php

namespace App\Domain\Setting;

interface SettingRepository
{
    public function getByUuid($uuid);
    public function getByHexUuid($hexUuid);

    public function save(SettingEntity $setting);

    public function delete(SettingEntity $setting);
}