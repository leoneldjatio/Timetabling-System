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

class TimeSlot extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['time_slot_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'time_slot_id';

    /**
     * Get the allocations associated to present timeslot
     *
     * @param  none
     * @return  Object
     */

    public function allocations()
    {

        return $this->hasMany('App\Allocation', 'time_slots_time_slot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weekDays(){
        return $this->belongsTo('App\WeekDay');
    }
}
