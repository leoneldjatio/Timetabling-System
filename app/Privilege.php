<?php

namespace App;

/**
 * @author Go-Groups LTD
 *
 */

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{

    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['privilege_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'privilege_id';

    public function role()
    {
        return $this->belongsToMany('App\Role');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

}
