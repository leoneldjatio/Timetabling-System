<?php

namespace App;

/**
 * @author Go-Groups LTD
 *
 */

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['role_id'];

    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */


    protected $primaryKey = 'role_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function privileges()
    {
        return $this->belongsToMany('App\Privilege', 'roles_has_privileges', 'roles_role_id', 'privileges_privilege_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->hasOne('App\User', 'roles_role_id');
    }
}
