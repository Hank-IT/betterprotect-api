<?php

namespace App\Services;

use App\Models\ClientSenderAccess;
use Illuminate\Database\Eloquent\Model;

class Access
{
    public function store(array $data): Model
    {
        $model = new ClientSenderAccess;

        $model->fill($data);

        $model->save();

        app(Orderer::class, ['model' => $model])->reOrder();

        return $model;
    }
}