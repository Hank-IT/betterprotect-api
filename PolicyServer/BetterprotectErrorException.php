<?php

class BetterprotectErrorException extends Exception
{
    protected $action;

    public function __construct(string $message, string $action = 'dunno') {
        parent::__construct($message, 0, null);

        $this->action = $action;

        $logger = new Logger;

        $logger->log($message, LOG_ERR);
    }

    public function getPostfixAction()
    {
        return $this->action;
    }
}