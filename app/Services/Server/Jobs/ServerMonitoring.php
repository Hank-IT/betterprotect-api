<?php

namespace App\Services\Server\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException;

class ServerMonitoring
{
    public function run()
    {
        // ToDo
        // Run every minute
        // Check if databases are reachable
        // Check if ssh reachable
        // Get queue items




        if (! $server->logDatabase()->available()) {
            throw ValidationException::withMessages(['log_db_host' => 'Datenbank ist nicht verfügbar.']);
        }

        if (! $server->postfixDatabase()->available()) {
            throw ValidationException::withMessages(['postfix_db_host' => 'Datenbank ist nicht verfügbar.']);
        }
    }

    protected function validateConsole()
    {
        try {
            return tap($console = $this->server->console(), function($console) {
                $console->available();
            })->access();
        } catch(ErrorException $exception) {
            throw ValidationException::withMessages(['ssh_user' => 'Verbindung fehlgeschlagen.']);
        } catch(PublicKeyMismatchException $exception) {
            Log::debug($this->server->hostname . ' public key: ' . $console->getPublicKey());
            throw ValidationException::withMessages(['ssh_public_key' => 'Public Key stimmt nicht überein.']);
        }
    }

    protected function validateSudo()
    {
        $this->console->bin($this->server->ssh_command_sudo . ' -h')->exec();

        Log::debug($this->console->getOutput());

        if ($this->console->getExitStatus() !== 0) {
            throw ValidationException::withMessages(['ssh_command_sudo' => 'Befehl konnte nicht ausgeführt werden.']);
        }
    }

    protected function validatePostqueue()
    {
        $this->console->sudo($this->server->ssh_command_sudo . ' -n')->bin($this->server->ssh_command_postqueue)->param('-j')->exec();

        Log::debug($this->console->getOutput());

        if ($this->console->getExitStatus() !== 0) {
            throw ValidationException::withMessages(['ssh_command_postqueue' => 'Befehl konnte nicht ausgeführt werden.']);
        }
    }

    protected function validatePostsuper()
    {
        $this->console->sudo($this->server->ssh_command_sudo . ' -n')->bin($this->server->ssh_command_postsuper)->exec();

        Log::debug($this->console->getOutput());

        if ($this->console->getExitStatus() !== 0) {
            throw ValidationException::withMessages(['ssh_command_postsuper' => 'Befehl konnte nicht ausgeführt werden.']);
        }
    }
}
