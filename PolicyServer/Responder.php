<?php

class Responder
{
    protected $action;

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    public function respond()
    {
        return "action=" . $this->action . "\n\n";
    }
}