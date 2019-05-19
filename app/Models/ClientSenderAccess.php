<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSenderAccess extends Model
{
    protected $table = 'client_sender_access';

    protected $fillable = [
        'payload',
        'type',
        'description',
        'action',
    ];
}