<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Station extends Model
{
    protected $fillable = [];

    public function settings(): HasOne
    {
        return $this->HasOne(StationSettings::class, 'station_id', 'id');
    }
}
