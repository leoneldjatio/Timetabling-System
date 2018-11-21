<?php

namespace App;

/**
 * @author Go-Groups LTD
 *
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use Notifiable;
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */
    protected $guarded = ['student_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'student_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    //protected $table = 'courses_has_students';
    public function courses()
    {
        return $this->belongsToMany('App\Course', 'courses_has_students','students_student_id','courses_course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departments()
    {
        return $this->belongsTo('App\Department');
    }


    public function routeNotificationForNexmo()
    {
        return $this->phone_number;
    }

}
