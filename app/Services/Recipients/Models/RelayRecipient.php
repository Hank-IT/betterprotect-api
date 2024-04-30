<?php

namespace App\Services\Recipients\Models;

use App\Concerns\SerializesDate;
use App\Support\Activatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelayRecipient extends Model
{
    use Activatable, SerializesDate, HasFactory;

    protected $guarded = [];

    public function setActionAttribute($value)
    {
        $this->attributes['action'] = strtoupper($value);
    }
}
