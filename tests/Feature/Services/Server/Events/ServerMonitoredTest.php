<?php

namespace Tests\Feature\Services\Server\Events;

use App\Services\Server\dtos\ServerState;
use App\Services\Server\dtos\ServerStateCheckResult;
use App\Services\Server\Events\ServerMonitored;
use Tests\TestCase;

class ServerMonitoredTest extends TestCase
{
    public function test_broadcast_with()
    {
        $state = new ServerState([
            'postfix-database-available' => new ServerStateCheckResult(true),
            'log-database-available' => new ServerStateCheckResult(true),
            'postqueue-executable' => new ServerStateCheckResult(true),
            'postsuper-executable' => new ServerStateCheckResult(true),
            'sudo-executable' => new ServerStateCheckResult(true),
            'ssh-connection' => new ServerStateCheckResult(true),
        ]);

        $event = new ServerMonitored($state);

        $data = $event->broadcastWith();

        $this->assertTrue($data['postfix-database-available']->getAvailable());
        $this->assertTrue($data['log-database-available']->getAvailable());
        $this->assertTrue($data['postqueue-executable']->getAvailable());
        $this->assertTrue($data['postsuper-executable']->getAvailable());
        $this->assertTrue($data['sudo-executable']->getAvailable());
        $this->assertTrue($data['ssh-connection']->getAvailable());
    }

    public function test_broadcast_as()
    {
        $state = new ServerState([
            'postfix-database-available' => new ServerStateCheckResult(true),
            'log-database-available' => new ServerStateCheckResult(true),
            'postqueue-executable' => new ServerStateCheckResult(true),
            'postsuper-executable' => new ServerStateCheckResult(true),
            'sudo-executable' => new ServerStateCheckResult(true),
            'ssh-connection' => new ServerStateCheckResult(true),
        ]);

        $event = new ServerMonitored($state);

        $this->assertEquals('server.monitored', $event->broadcastAs());
    }

    public function test_broadcast_on()
    {
        $state = new ServerState([
            'postfix-database-available' => new ServerStateCheckResult(true),
            'log-database-available' => new ServerStateCheckResult(true),
            'postqueue-executable' => new ServerStateCheckResult(true),
            'postsuper-executable' => new ServerStateCheckResult(true),
            'sudo-executable' => new ServerStateCheckResult(true),
            'ssh-connection' => new ServerStateCheckResult(true),
        ]);

        $event = new ServerMonitored($state);

        $this->assertEquals('private-monitoring', $event->broadcastOn()->name);
        $this->assertEquals('private-monitoring', (string) $event->broadcastOn());
    }
}
