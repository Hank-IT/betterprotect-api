<?php

namespace App\Http\Controllers\Server;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException;

class ConsoleController extends Controller
{
    protected $console;

    protected $server;

    public function store(Request $request, Server $server)
    {
        $this->validate($request, [
            'ssh_user' => 'required|string',
            'ssh_public_key' => 'required|string',
            'ssh_private_key' => 'required|string',
            'ssh_command_sudo' => 'required|string',
            'ssh_command_postqueue' => 'required|string',
            'ssh_command_postsuper' => 'required|string',
        ]);

        $server->fill([
            'ssh_user' => $request->ssh_user,
            'ssh_public_key' => $request->ssh_public_key,
            'ssh_private_key' => $request->ssh_private_key,
            'ssh_command_sudo' => $request->ssh_command_sudo,
            'ssh_command_postqueue' => $request->ssh_command_postqueue,
            'ssh_command_postsuper' => $request->ssh_command_postsuper,
            'ssh_feature_enabled' => true,
        ]);

        $this->server = $server;

        $this->console = $this->validateConsole();

        $this->validateSudo();

        $this->validatePostsuper();

        $this->validatePostqueue();

        $server->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Konsole erfolgreich aktiviert.',
            'data' => $server
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, Server $server)
    {
        $this->validate($request, [
            'ssh_user' => 'required|string',
            'ssh_public_key' => 'required|string',
            'ssh_private_key' => 'nullable|string',
            'ssh_command_sudo' => 'required|string',
            'ssh_command_postqueue' => 'required|string',
            'ssh_command_postsuper' => 'required|string',
            'ssh_feature_enabled' => 'required|boolean',
        ]);

        $server->fill([
            'ssh_user' => $request->ssh_user,
            'ssh_public_key' => $request->ssh_public_key,
            'ssh_command_sudo' => $request->ssh_command_sudo,
            'ssh_command_postqueue' => $request->ssh_command_postqueue,
            'ssh_command_postsuper' => $request->ssh_command_postsuper,
            'ssh_feature_enabled' => $request->ssh_feature_enabled,
        ]);

        if ($request->exists('ssh_private_key')) {
            $server->ssh_private_key = $request->ssh_private_key;
        }

        $this->server = $server;

        $this->console = $this->validateConsole();

        $this->validateSudo();

        $this->validatePostsuper();

        $this->validatePostqueue();

        $server->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Konsole erfolgreich aktualisiert.',
            'data' => $server
        ]);
    }

    public function show(Server $server)
    {
        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $server->only(['ssh_user', 'ssh_public_key', 'ssh_command_sudo', 'ssh_command_postqueue', 'ssh_command_postsuper', 'ssh_feature_enabled']),
        ]);
    }

    protected function validateConsole()
    {
        try {
            return tap($this->server->console(), function($console) {
                $console->available();
            })->access();
        } catch(\ErrorException $exception) {
            throw ValidationException::withMessages(['ssh_user' => 'Verbindung fehlgeschlagen.']);
        } catch(PublicKeyMismatchException $exception) {
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
