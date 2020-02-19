<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class Orderer
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function moveUp()
    {
        static::reOrder();

        $this->model->where('priority', '=', $this->model->priority - 1)->increment('priority');

        $this->model->decrement('priority');

        static::reOrder();
    }

    public function moveDown()
    {
        static::reOrder();

        $this->model->where('priority', '=', $this->model->priority + 1)->decrement('priority');

        $this->model->increment('priority');

        static::reOrder();
    }

    public function reOrder()
    {
        $priority = 0;
        $this->model->orderBy('priority')->get()->each(function(Model $model) use(&$priority) {
            $model->priority = $priority;
            $model->save();
            $priority++;
        });
    }
}