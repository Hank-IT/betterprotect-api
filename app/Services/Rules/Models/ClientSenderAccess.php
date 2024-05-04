<?php

namespace App\Services\Rules\Models;

use App\Concerns\SerializesDate;
use App\Services\Order\Contracts\Orderable;
use App\Support\Activatable;
use Database\Factories\ClientSenderAccessFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSenderAccess extends Model implements Orderable
{
    use Activatable, SerializesDate, HasFactory;

    protected $table = 'client_sender_access';

    protected $guarded = [];

    protected static function newFactory()
    {
        return ClientSenderAccessFactory::new();
    }

    public function getOrderColumn(): string
    {
        return 'priority';
    }

    public function getOrderColumnValue(): int
    {
        return $this->priority;
    }
}
