<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilterException extends Model
{
    protected $fillable = ['client_type', 'client_payload'];

    protected $with = ['milters'];

    public function milters()
    {
        return $this->belongsToMany(Milter::class);
    }
}
