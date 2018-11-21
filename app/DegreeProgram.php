<?php

namespace App;

/**
 * @author Go-Groups LTD
 *
 */

use Illuminate\Database\Eloquent\Model;

class DegreeProgram extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['degree_program_id'];
    /**
     * The attribute that should be used as primary key
     *
     * @property  primaryKey
     * @var string
     *
     */
    protected $primaryKey = 'degree_program_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function faculty()
    {
        return $this->belongsToMany('App\Faculty', 'faculties_has_degree_programs', 'faculties_faculty_id', 'degree_programs_degree_program_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function buildings()
    {
        return $this->belongsToMany('App\Building', 'degree_programs_has_buildings', 'degree_programs_degree_program_id', 'buildings_building_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function levels()
    {
        return $this->belongsToMany('App\Level', 'degree_programs_has_levels', 'degree_programs_degree_program_id', 'levels_level_id');
    }
}
