<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class UserModule
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int module_id
 */
class UserModule extends Model
{
    protected $table = 'user_modules';
      protected  $guarded = [];
}
