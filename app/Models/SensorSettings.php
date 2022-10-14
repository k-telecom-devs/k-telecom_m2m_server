<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SensorSettings extends Model
{
    protected $table = "sensors_settings";
    protected $fillable = [
        'name','sleep', 'sensor_id'
    ];

    public function sleepTime(): HasMany
    {
        return $this->hasMany(SensorSettings::class, 'sensor_id', 'id')->orderByDesc('id');
    }

}
