<?php

namespace App\Models;

use App\Support\Activatable;
use Illuminate\Database\Eloquent\Model;

class RelayDomain extends Model
{
    use Activatable;

    protected $fillable = ['domain'];
}
