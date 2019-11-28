<?php

namespace App\Models;

use App\Support\Activatable;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use Activatable;

    protected $fillable = ['domain', 'transport', 'nexthop', 'nexthop_type', 'nexthop_mx', 'data_source'];
}
