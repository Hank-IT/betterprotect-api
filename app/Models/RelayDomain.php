<?php

namespace App\Models;

use App\Support\Activatable;
use App\Concerns\SerializesDate;
use Illuminate\Database\Eloquent\Model;

class RelayDomain extends Model
{
    use Activatable, SerializesDate;

    protected $fillable = ['domain'];
}
