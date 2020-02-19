<?php

namespace App\Models;

use App\Support\Activatable;
use Illuminate\Database\Eloquent\Model;

class MilterException extends Model
{
    use Activatable;

    protected $fillable = ['client_type', 'client_payload'];

    protected $with = ['milters'];

    public function milters()
    {
        return $this->belongsToMany(Milter::class);
    }
}
