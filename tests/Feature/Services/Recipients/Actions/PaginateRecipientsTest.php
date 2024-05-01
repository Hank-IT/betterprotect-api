<?php

namespace Tests\Feature\Services\Recipients\Actions;

use App\Services\Recipients\Actions\PaginateRecipients;
use App\Services\Recipients\Models\RelayRecipient;
use Tests\TestCase;

class PaginateRecipientsTest extends TestCase
{
    public function test()
    {
        RelayRecipient::factory()->count(10)->create();

        $result = app(PaginateRecipients::class)->execute(1, 5);

        $this->assertCount(5, $result->toArray()['data']);
    }

    public function testWithSearch()
    {
        RelayRecipient::factory()->count(10)->create();

        RelayRecipient::factory()->create([
            'payload' => 'mail@contoso.com'
        ]);

        $result = app(PaginateRecipients::class)->execute(1, 5, 'mail@contoso.com');

        $this->assertCount(1, $result->toArray()['data']);
    }
}
