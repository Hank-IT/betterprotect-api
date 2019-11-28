<?php

namespace App\Support;

trait Activatable
{
    public function activate()
    {
        $this->active = 1;

        $this->save();
    }

    public function deactivate()
    {
        $this->active = 0;

        $this->save();
    }
}
