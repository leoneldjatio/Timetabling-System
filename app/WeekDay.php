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

class WeekDay extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['week_day_id'];

    /**
     * The attribute that should be used as primary key
     *
     * @property  primaryKey
     * @var string
     *
     */

    protected $primaryKey = 'week_day_id';

    /**
     * Establish one to many relationship with TimeSlot Model
     *
     * @param  none
     * @return  Object
     */

    public function timeSlots()
    {

        return $this->hasMany('App\TimeSlot', 'week_days_week_day_id');
    }


    /**
     * Establish an inverse relationship with Semester Model
     *
     * @param  none
     * @return  Object
     */

    public function semesters()
    {

        return $this->belongsTo('App\Semester');
    }
}
