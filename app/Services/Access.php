<?php

namespace App\Services;

use App\Exceptions\ErrorException;
use App\Models\ClientSenderAccess;
use Illuminate\Database\Eloquent\Model;

class Access
{
    public function store(array $data): Model
    {
        $modal = new ClientSenderAccess;

        $modal->fill($data);

        $modal->save();

        return $modal;
    }
}