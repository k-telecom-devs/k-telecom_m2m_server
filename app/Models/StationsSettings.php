<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationsSettings extends Model
{
    protected $table = "stations_settings";
    protected $fillable = [
        'name', 'station_id'
    ];


}
