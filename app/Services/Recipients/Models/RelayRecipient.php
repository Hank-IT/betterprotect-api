<?php

namespace App\Services\Recipients\Models;

use App\Concerns\SerializesDate;
use App\Services\Activation\Concerns\IsActivatable;
use App\Services\Activation\Contracts\Activatable;
use Database\Factories\RelayRecipientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelayRecipient extends Model implements Activatable
{
    use SerializesDate, HasFactory, IsActivatable;

    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function setActionAttribute($value)
    {
        $this->attributes['action'] = strtoupper($value);
    }

    protected static function newFactory()
    {
        return RelayRecipientFactory::new();
    }
}
