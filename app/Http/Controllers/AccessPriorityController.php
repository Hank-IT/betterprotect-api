<?php

namespace App\Http\Controllers;

use App\Models\ClientSenderAccess;

class AccessPriorityController extends Controller
{
    public function moveUp(ClientSenderAccess $clientSenderAccess)
    {
        $this->reOrder();

        ClientSenderAccess::where('priority', '=', $clientSenderAccess->priority - 1)->increment('priority');

        $clientSenderAccess->decrement('priority');

        $this->reOrder();
    }

    public function moveDown(ClientSenderAccess $clientSenderAccess)
    {
        $this->reOrder();

        ClientSenderAccess::where('priority', '=', $clientSenderAccess->priority + 1)->decrement('priority');

        $clientSenderAccess->increment('priority');

        $this->reOrder();
    }

    protected function reOrder()
    {
        $priority = 0;
        ClientSenderAccess::orderBy('priority')->get()->each(function($clientSenderAccess) use(&$priority) {
            $clientSenderAccess->priority = $priority;
            $clientSenderAccess->save();
            $priority++;
        });
    }
}
