<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelayRecipient extends Model
{
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
