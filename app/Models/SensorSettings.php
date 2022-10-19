<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SensorSettings extends Model
{
    protected $table = "sensor_settings";
    protected $fillable = [
        'name','sleep', 'sensor_id'
    ];

    public function version(): HasOne
    {
        return $this->hasOne(Version::class);
    }
}
