<?php

namespace App\Services\RelayDomains\Models;

use App\Concerns\SerializesDate;
use App\Support\Activatable;
use Illuminate\Database\Eloquent\Model;

class RelayDomain extends Model
{
    use Activatable, SerializesDate;

    protected $guarded = [];
}
