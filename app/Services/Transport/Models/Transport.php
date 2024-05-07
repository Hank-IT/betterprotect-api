<?php

namespace App\Services\Transport\Models;

use App\Services\Activation\Contracts\Activatable;
use App\Services\Activation\Concerns\IsActivatable;
use Database\Factories\TransportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model implements Activatable
{
    use HasFactory, IsActivatable;

    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected static function newFactory()
    {
        return TransportFactory::new();
    }
}
