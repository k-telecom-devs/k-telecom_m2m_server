<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationSettings extends Model
{
    protected $table = "station_settings";
    protected $fillable = [
        'name', 'station_id'
    ];


}
