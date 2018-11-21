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

class Semester extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['semester_id'];

    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */

    protected $primaryKey = 'semester_id';


    /**
     * Get the days of the week associated to present semester
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function weekDays()
    {

        return $this->hasMany('App\WeekDay', 'semesters_semester_id');
    }


    /**
     *  Establish an inverse relationship with Year Model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function years()
    {

        return $this->belongsTo('App\Year');
    }
}
