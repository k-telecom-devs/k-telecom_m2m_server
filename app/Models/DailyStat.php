<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStat extends Model
{
    protected $fillable = [
        'average', 'measurements_number'
    ];
    protected $table = 'daily_stats';
}