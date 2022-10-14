<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorsSettings extends Model
{
    protected $table = "sensors_settings";
    protected $fillable = [
        'name','sleep', 'sensor_id'
    ];
    
    public function sleepTime(): HasMany
    {
        return $this->hasMany(SensorsSettings::class, 'sensor_id', 'id')->orderByDesc('id');
    }

}
