<?php

namespace App\Services\Transport\Models;

use App\Concerns\SerializesDate;
use App\Support\Activatable;
use Database\Factories\TransportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use Activatable, SerializesDate, HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return TransportFactory::new();
    }
}
