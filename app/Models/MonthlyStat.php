<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyStat extends Model
{
    protected $fillable = [
        'average', 'measurements_number'
    ];
    protected $table = 'monthly_stats';
}