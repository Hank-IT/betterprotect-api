<?php

namespace Controllers\MailLogging;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class ServerLogControllerTest extends TestCase
{
    public function test()
    {
        $user = User::factory()->create();

        $this->be($user);

        $query = [
            'startDate' => '2023/11/01 00:00',
            'endDate' => '2023/11/30 23:59',
        ];

        $route = route('server.log.show') . '?' . http_build_query($query);

        $this->get($route)->dump()->assertSuccessful();
    }
}
