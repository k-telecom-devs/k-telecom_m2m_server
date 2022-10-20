<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [];
    
    public function settings(): HasOne
    {
        return $this->HasOne(StationSettings::class, 'station_id', 'id');
    }
}
