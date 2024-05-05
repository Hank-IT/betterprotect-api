<?php

namespace App\Services\Milter\Models;

use App\Concerns\SerializesDate;
use App\Services\Order\Contracts\Orderable;
use App\Support\Activatable;
use Database\Factories\MilterExceptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilterException extends Model implements Orderable
{
    use Activatable, SerializesDate, HasFactory;

    protected $guarded = [];

    public function milters()
    {
        return $this->belongsToMany(Milter::class);
    }

    protected static function newFactory()
    {
        return MilterExceptionFactory::new();
    }

    public function getOrderColumn(): string
    {
        return 'priority';
    }

    public function getOrderColumnValue(): int
    {
        return $this->priority;
    }
}
