<?php

namespace App\Services\PostfixPolicyInstallation;

use App\Models\MilterException;

class MilterExceptionHandler extends AbstractHandler
{
    public function install()
    {
        $this->task->update(['message' => 'Milter Ausnahmen werden aktualisiert...']);

        $this->insert($this->getMilterExceptionRows());
    }

    public function table()
    {
        return 'milter_exceptions';
    }

    protected function getMilterExceptionRows()
    {
        $milterExceptions = MilterException::where('active', '=', 1)->get();

        $milterExceptions->map(function($row) {
            \Log::debug($row);

            
        });

        return [];
    }
}