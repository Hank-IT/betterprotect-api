<?php

namespace App\Services\Rules\Models;

use App\Concerns\SerializesDate;
use App\Support\Activatable;
use Illuminate\Database\Eloquent\Model;

class ClientSenderAccess extends Model
{
    use Activatable, SerializesDate;

    protected $table = 'client_sender_access';

    protected $guarded = [];
}
