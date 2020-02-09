<?php

namespace App\Services;

use App\Models\ClientSenderAccess;

class AccessOrder
{
    protected $clientSenderAccess;

    public function __construct(ClientSenderAccess $clientSenderAccess)
    {
        $this->clientSenderAccess = $clientSenderAccess;
    }

    public function moveUp()
    {
        static::reOrder();

        ClientSenderAccess::where('priority', '=', $this->clientSenderAccess->priority - 1)->increment('priority');

        $this->clientSenderAccess->decrement('priority');

        static::reOrder();
    }

    public function moveDown()
    {
        static::reOrder();

        ClientSenderAccess::where('priority', '=', $this->clientSenderAccess->priority + 1)->decrement('priority');

        $this->clientSenderAccess->increment('priority');

        static::reOrder();
    }

    public static function reOrder()
    {
        $priority = 0;
        ClientSenderAccess::orderBy('priority')->get()->each(function($clientSenderAccess) use(&$priority) {
            $clientSenderAccess->priority = $priority;
            $clientSenderAccess->save();
            $priority++;
        });
    }
}