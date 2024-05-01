<?php

namespace Tests\Feature\Services\Transport\Actions;

use App\Services\Transport\Actions\PaginateTransports;
use App\Services\Transport\Models\Transport;
use Tests\TestCase;

class PaginateTransportsTest extends TestCase
{
    public function test()
    {
        Transport::factory()->count(10)->create();

        $result = app(PaginateTransports::class)->execute(1, 5);

        $this->assertCount(5, $result->toArray()['data']);
    }

    public function testWithSearch()
    {
        Transport::factory()->count(10)->create();

        Transport::factory()->create([
            'domain' => 'contoso.com'
        ]);

        $result = app(PaginateTransports::class)->execute(1, 5, 'contoso.com');

        $this->assertCount(1, $result->toArray()['data']);
    }
}
