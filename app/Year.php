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

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['year_id'];

    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */

    protected $primaryKey = 'year_id';

    /**
     * Get the semesters associated to this year
     *
     * @param  none
     * @return  Object
     */

    public function semesters()
    {

        return $this->hasMany('App\Semester', 'years_year_id');
    }
}
