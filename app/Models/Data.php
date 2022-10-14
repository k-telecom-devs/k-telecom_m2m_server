<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $fillable = [
        'value', 'sensor_id'
    ];
}
