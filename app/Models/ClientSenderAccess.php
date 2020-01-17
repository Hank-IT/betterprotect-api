<?php

namespace App\Models;

use App\Support\Activatable;
use Illuminate\Database\Eloquent\Model;

class ClientSenderAccess extends Model
{
    use Activatable;

    protected $table = 'client_sender_access';

    protected $fillable = [
        'client',
        'sender',
        'client_type',
        'description',
        'action',
    ];
}
