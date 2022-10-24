<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subgroup extends Model
{
    protected $table = "subgroup";
    protected $fillable = [
        'subgroup_name',
    ];
}
