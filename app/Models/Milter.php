<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milter extends Model
{
    protected $fillable = [
        'name',
        'definition',
        'description'
    ];
}
