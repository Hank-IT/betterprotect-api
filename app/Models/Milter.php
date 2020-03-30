<?php

namespace App\Models;

use App\Concerns\SerializesDate;
use Illuminate\Database\Eloquent\Model;

class Milter extends Model
{
    use SerializesDate;

    protected $fillable = [
        'name',
        'definition',
        'description'
    ];
}
