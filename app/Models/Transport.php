<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    protected $fillable = ['domain', 'transport', 'nexthop', 'nexthop_type', 'nexthop_mx', 'meta'];
}
