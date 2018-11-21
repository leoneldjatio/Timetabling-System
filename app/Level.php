<?php

namespace App;

/**
 * @author Go-Groups LTD
 *
 */

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['level_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'level_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function levelCourses()
    {
        return $this->hasMany('App\Course', 'levels_level_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function degreeProgram()
    {
        return $this->belongsToMany('App\Level', 'degree_programs_has_levels', 'levels_level_id', 'degree_programs_degree_program_id');
    }
}
