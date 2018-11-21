<?php

namespace App;

/**
 * @author Go-Groups LTD
 *
 */

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['category_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'category_id';

    public function categoryPrivileges()
    {
        return $this->hasMany('App/Privilege', 'categories_category_privilege');
    }
}
