<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class Orderer
{
    public function moveUp(Model $model)
    {
        static::reOrder($model);

        $model->where('priority', '=', $model->priority - 1)->increment('priority');

        $model->decrement('priority');

        static::reOrder($model);
    }

    public function moveDown(Model $model)
    {
        static::reOrder($model);

        $model->where('priority', '=', $model->priority + 1)->decrement('priority');

        $model->increment('priority');

        static::reOrder($model);
    }

    public function reOrder(Model $model)
    {
        $priority = 0;
        $model->orderBy('priority')->get()->each(function(Model $model) use(&$priority) {
            $model->priority = $priority;
            $model->save();
            $priority++;
        });
    }
}
