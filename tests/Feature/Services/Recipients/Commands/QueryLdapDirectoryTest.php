<?php

namespace Tests\Feature\Services\Recipients\Commands;

use App\Services\Recipients\Jobs\RefreshLdapRecipients;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueryLdapDirectoryTest extends TestCase
{
    public function test()
    {
        Queue::fake();

        $this->artisan('ldap:query')->assertSuccessful();

        Queue::assertPushed(RefreshLdapRecipients::class);
    }
}
