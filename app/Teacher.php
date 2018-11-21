<?php

namespace App;

/**
 * @author Go-Groups LTD
 * Created by PhpStorm.
 * User: ewangclarks
 * edited by: Leonel Foma
 * Date: 13/01/17
 * Time: 3:10 PM
 *
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Teacher extends Model
{
    use Notifiable;
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['teacher_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'teacher_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
   // protected $table = 'teachers_has_courses';
    public function courses()
    {
        return $this->belongsToMany('App\Course','teachers_has_courses','teachers_teacher_id','courses_course_id');
    }

    public function departments(){
        return $this->belongsToMany('App\Department','departments_has_teachers','departments_department_id','teachers_teacher_id');
    }

    /**
     * @return mixed
     */
    public function routeNotificationForNexmo()
    {
        return $this->phone_number;
    }
}
