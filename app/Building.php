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

class Building extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['building_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'building_id';


    public function rooms()
    {

        return $this->hasMany('App\Room', 'buildings_building_id');

    }

    public function degreePrograms()
    {
        return $this->belongsToMany('App\DegreePrograms', 'degree_programs_has_buildings', 'degree_programs_degree_program_id', 'buildings_building_id');
    }
}
