<?php

namespace App\Postfix;

use App\Exceptions\ErrorException;
use App\Models\Server;

class Queue
{
    protected $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function get()
    {
        $console = $this->server->console()->access();

        $console->sudo($this->server->ssh_command_sudo)
            ->bin($this->server->ssh_command_postqueue)
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
            $mail = json_decode($mail, true);
            $mail['server'] = $this->server->hostname;
            $mail['server_id'] = $this->server->id;
            $mails[] = $mail;
        }

        return $mails;
    }

    public function exists(string $queueId)
    {
        $this->get();


    }

    public function flush()
    {
        $console = $this->server->console()->access();

        $console->sudo($this->server->ssh_command_sudo)
            ->bin($this->server->ssh_command_postqueue)
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
        $console = $this->server->console()->access();

        $console->sudo($this->server->ssh_command_sudo)
            ->bin($this->server->ssh_command_postsuper)
            ->param('-d')
            ->param($queueId)
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return $console->getOutput();
    }
}
