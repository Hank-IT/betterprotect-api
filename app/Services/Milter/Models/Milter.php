<?php

namespace App\Services\Milter\Models;

use App\Concerns\SerializesDate;
use Database\Factories\MilterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milter extends Model
{
    use SerializesDate, HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return MilterFactory::new();
    }
}
