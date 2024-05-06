<?php

namespace App\Services\RelayDomains\Models;

use App\Concerns\SerializesDate;
use App\Support\Activatable;
use Database\Factories\RelayDomainFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelayDomain extends Model
{
    use Activatable, SerializesDate, HasFactory;

    protected $guarded = [];

    public static function newFactory()
    {
        return RelayDomainFactory::new();
    }
}

