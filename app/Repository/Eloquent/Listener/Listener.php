<?php

namespace App\Repository\Eloquent\Listener;

use App\Repository\Eloquent\Rule\Rule;
use Illuminate\Database\Eloquent\Model;

class Listener extends Model
{
    protected $table = 'listeners';

    public function rules()
    {
        return $this->hasMany(Rule::class);
    }
}