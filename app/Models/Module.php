<?php

namespace App\Models;

use stdClass;

/**
 * Class Module
 * @package App\Models
 * @property int id
 * @property string name
 * @property string slug
 * @property string description
 */
class Module extends Model
{
    protected $table  = 'modules';

    public function users(){
        return $this->belongsToMany(User::class, 'module_users');
    }

    public function getDetails()
    {
        $module = new stdClass();
        $module->id = $this->id;
        $module->name = $this->name;
        $module->slug = $this->slug;
        $module->description = $this->description;
        return $module;
    }
}
