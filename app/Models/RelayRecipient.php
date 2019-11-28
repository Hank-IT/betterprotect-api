<?php

namespace App\Models;

use App\Support\Activatable;
use Illuminate\Database\Eloquent\Model;

class RelayRecipient extends Model
{
    use Activatable;

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
