<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $fillable = [
        'user_id'
    ];

    //TODO: допилить версию (связи с сеттингсами)
}
