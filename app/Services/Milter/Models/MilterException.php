<?php

namespace App\Services\Milter\Models;

use App\Services\Activation\Concerns\IsActivatable;
use App\Services\Activation\Contracts\Activatable;
use App\Services\Order\Concerns\HasOrder;
use App\Services\Order\Contracts\Orderable;
use Database\Factories\MilterExceptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilterException extends Model implements Orderable, Activatable
{
    use HasFactory, IsActivatable, HasOrder;

    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function milters()
    {
        return $this->belongsToMany(Milter::class);
    }

    protected static function newFactory()
    {
        return MilterExceptionFactory::new();
    }
}
