<?php

namespace App\Postfix;

use App\Exceptions\ErrorException;
use App\Models\Server;

class Queue
{
    protected $server;

    public function __construct(Server $server)
    {
        if (empty($server->sudo)) {
            throw new ErrorException('Terminalzugriff ist nicht konfiguriert.');
        }

        $this->server = $server;
    }

    /**
     * @return array
     * @throws ErrorException
     * @throws \MrCrankHank\ConsoleAccess\Exceptions\MissingCommandException
     * @throws \MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException
     */
    public function get()
    {
        $console = $this->server->console();

        $console->sudo($this->server->sudo)
            ->bin($this->server->sudo)
            ->param('-j')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        $output = $console->getOutput();

        // each mail is its own json object
        $output = explode("\n", $output);

        // remove last newline
        array_pop($output);

        $mails = [];

        foreach($output as $mail) {
            $mails[] = json_decode($mail, true);
        }

        return $mails;
    }

    public function flush()
    {
        $console = $this->server->console();

        $console->sudo($this->server->sudo)
            ->bin($this->server->postqueue)
            ->param('-f')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return $console->getOutput();
    }

    /**
     * @param $queueId
     * @return mixed
     * @throws ErrorException
     * @throws \MrCrankHank\ConsoleAccess\Exceptions\MissingCommandException
     * @throws \MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException
     */
    public function deleteMail($queueId)
    {
        $console = $this->server->console();

        $console->sudo($this->server->sudo)
            ->bin($this->server->postsuper)
            ->param('-d')
            ->param($queueId)
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return $console->getOutput();
    }
}
