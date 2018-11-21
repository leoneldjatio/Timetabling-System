<?php

namespace App;

/**
 * @author Go-Groups LTD
 *
 */

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['course_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'course_id';

    /**
     * Eloquent many to many relationship between students and courses
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany('App\Student', 'courses_has_students', 'courses_course_id', 'students_student_id');
    }

    /**
     * Eloquent many to many relationship between teachers and courses
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teachers()
    {
        return $this->belongsToMany('App\Teacher', 'teachers_has_courses', 'courses_course_id', 'teachers_teacher_id');
    }

    /**
     * Eloquent inverse relation for courses with levels
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        return $this->belongsTo('App\Level');
    }
}
