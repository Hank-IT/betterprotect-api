<?php

namespace App\Models;

use App\Support\Activatable;
use App\Concerns\SerializesDate;
use Illuminate\Database\Eloquent\Model;

class RelayRecipient extends Model
{
    use Activatable, SerializesDate;

    protected $fillable = [
        'data_source',
        'payload',
        'action',
    ];

    public function setActionAttribute($value)
    {
        $this->attributes['action'] = strtoupper($value);
    }
}
