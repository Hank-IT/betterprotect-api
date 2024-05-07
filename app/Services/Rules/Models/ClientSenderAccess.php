<?php

namespace App\Services\Rules\Models;

use App\Services\Activation\Concerns\IsActivatable;
use App\Services\Order\Concerns\HasOrder;
use App\Services\Order\Contracts\Orderable;
use Database\Factories\ClientSenderAccessFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Activation\Contracts\Activatable;

class ClientSenderAccess extends Model implements Orderable, Activatable
{
    use HasFactory, IsActivatable, HasOrder;

    protected $table = 'client_sender_access';

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $guarded = [];

    protected static function newFactory()
    {
        return ClientSenderAccessFactory::new();
    }
}
