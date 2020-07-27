<?php

namespace App\Models\Person;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use DB;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at'
    ];


    /**
     *  Get query of permitted model for user
     * @param string $model the full model class name
     * @param string|array $permissions single permission or array of permission
     * @param string $keyColumn key column to match with team's name
     * @return Illuminate\Database\Eloquent\Builder
     */


    public function permissions() {
        return $this->belongsToMany('App\Models\Person\Permission');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Person\Role');
    }
}
