<?php

namespace App\Models;

use App\Concerns\SerializesDate;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use SerializesDate;

    public $timestamps = false;
}
