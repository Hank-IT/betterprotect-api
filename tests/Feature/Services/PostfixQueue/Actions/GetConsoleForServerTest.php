<?php

namespace Tests\Feature\Services\PostfixQueue\Actions;

use App\Services\Server\Actions\GetConsole;
use App\Services\Server\dtos\SSHDetails;
use App\Services\Server\Models\Server;
use HankIT\ConsoleAccess\ConsoleAccess;
use Tests\TestCase;

class GetConsoleForServerTest extends TestCase
{
    public function test()
    {
        $sshDetails = SSHDetails::factory()->make();

        $console = app(GetConsole::class)->execute($sshDetails);

        $this->assertInstanceOf(ConsoleAccess::class, $console);
    }
}
