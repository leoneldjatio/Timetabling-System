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

class Configuration extends Model
{
    /**
     * The attribute that should not be mass assignable
     *
     * @var array
     *
     */

    protected $guarded = ['configuration_id'];

    /**
     * The attribute that should be used as primary key
     *
     * @property primaryKey
     * @var string
     *
     */

    protected $primaryKey = 'configuration_id';

}
