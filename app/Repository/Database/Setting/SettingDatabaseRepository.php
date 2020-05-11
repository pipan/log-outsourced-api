<?php

namespace App\Repository\Database\Setting;

use App\Domain\Setting\SettingEntity;
use App\Domain\Setting\SettingRepository;
use App\Repository\Database\UuidDatabaseRepository;
use Illuminate\Support\Facades\DB;

class SettingDatabaseRepository extends UuidDatabaseRepository implements SettingRepository
{
    public function __construct()
    {
        parent::__construct('setting', new SettingDatabaseResultAdapter());
    }

    public function save(SettingEntity $setting)
    {
        if ($setting->getId() == 0) {
            $this->create($setting);
        } else {
            $this->update($setting->getId(), $setting);
        }
        return $setting;
    }

    protected function create(SettingEntity $setting)
    {
        DB::table($this->table)
            ->insert([
                'uuid' => $setting->getUuid(),
                'data' => $setting->getData()
            ]);
    }

    protected function update($id, SettingEntity $setting)
    {
        DB::table($this->table)
            ->where('id', '=', $id)
            ->update([
                'data' => $setting->getData()
            ]);
    }

    public function delete(SettingEntity $setting)
    {
        return DB::table($this->table)
            ->where('id', '=', $setting->getId())
            ->delete();
    }
}