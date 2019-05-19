<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SenderAccess extends Model
{
    protected $table = 'sender_access';

    protected $fillable = [
        'payload',
        'type',
        'description',
        'action',
    ];
}
