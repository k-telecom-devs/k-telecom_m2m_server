<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorSettings extends Model
{
    protected $table = "sensor_settings";
    protected $fillable = [
        'name','sleep', 'sensor_id'
    ];


}
