<?php

namespace App\Services\Milter\Models;

use Database\Factories\MilterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milter extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return MilterFactory::new();
    }

    public function milterExceptions()
    {
        return $this->hasMany(MilterException::class);
    }
}
