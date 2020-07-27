<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function permissions() {
        return $this->belongsToMany('App\Models\Person\Permission');
    }

}
