<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * [Description Person]
 * Person model
 */
class Person extends Model
{
    protected $fillable = ['session_id','serial_number','age','title','fio','picture'];
}
