<?php

namespace App;

/**
 * @author Go-Groups LTD
 * Created by PhpStorm.
 * User: ewangclarks
 * Date: 13/01/17
 * Time: 3:10 PM
 *
 */

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
     */
    protected $guarded = ['user_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password', 'remember_token',
    ];

    /**
     * @param $userPrivilege
     * @return bool
     */
    public function hasPrivilege($userPrivilege)
    {
        foreach ($this->userRole()->get() as $role) {
            foreach ($role->privileges()->get() as $privilege) {
                if (strcmp($userPrivilege, $privilege->url) == 0)
                    return true;
            }
        }
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function userRole()
    {

        return $this->belongsTo('App\Role', 'roles_role_id');
    }
}
