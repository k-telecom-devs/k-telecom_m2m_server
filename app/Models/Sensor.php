<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sensor extends Model
{
    protected $fillable = [
        'mac', 'uptime', 'charge', 'station_id'
    ];

    public function data(): HasMany
    {
        return $this->hasMany(Data::class, 'sensor_id', 'id')->orderByDesc('id');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(SensorSettings::class, 'sensor_id', 'id');
    }
}
