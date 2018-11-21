<?php

namespace App;

/**
 * @author Go-Groups LTD
 * Created by PhpStorm.
 * User: ewangclarks
 * Date: 13/01/17
 * Time: 3:17 PM
 *
 */

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['allocation_id'];

    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */

    protected $primaryKey = 'allocation_id';


    /**
     * Establish  an inverse relationship with WeekDay Model
     *
     * @param  none
     * @return  Object
     *
     */

    public function timeSlots()
    {

        return $this->belongsTo('App\TimeSlot');
    }

}
