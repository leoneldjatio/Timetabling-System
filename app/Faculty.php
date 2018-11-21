<?php

namespace App;

/**
 * @author Go-Groups LTD
 *
 */

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['faculty_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'faculty_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departments()
    {
        return $this->hasMany('App\Department', 'faculties_faculty_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function degreePrograms()
    {
        return $this->belongsToMany('App\DegreeProgram', 'faculties_has_degree_programs', 'faculties_faculty_id', 'degree_programs_degree_program_id');
    }

}
