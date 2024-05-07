<?php

namespace App\Services\RelayDomains\Models;

use App\Concerns\SerializesDate;
use App\Services\Activation\Concerns\IsActivatable;
use Database\Factories\RelayDomainFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Activation\Contracts\Activatable;

class RelayDomain extends Model implements Activatable
{
    use SerializesDate, HasFactory, IsActivatable;

    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static function newFactory()
    {
        return RelayDomainFactory::new();
    }
}

