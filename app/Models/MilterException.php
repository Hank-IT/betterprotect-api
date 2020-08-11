<?php

namespace App\Models;

use App\Support\Activatable;
use App\Concerns\SerializesDate;
use Illuminate\Database\Eloquent\Model;

class MilterException extends Model
{
    use Activatable, SerializesDate;

    protected $fillable = ['client_type', 'client_payload'];

    protected $with = ['milters'];

    public function milters()
    {
        return $this->belongsToMany(Milter::class);
    }
}
