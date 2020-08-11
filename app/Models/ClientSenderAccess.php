<?php

namespace App\Models;

use App\Support\Activatable;
use App\Concerns\SerializesDate;
use Illuminate\Database\Eloquent\Model;

class ClientSenderAccess extends Model
{
    use Activatable, SerializesDate;

    protected $table = 'client_sender_access';

    protected $fillable = [
        'client_payload',
        'client_type',
        'sender_payload',
        'sender_type',
        'message',
        'description',
        'action',
        'priority',
    ];
}
