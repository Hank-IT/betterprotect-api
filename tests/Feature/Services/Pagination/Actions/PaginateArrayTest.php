<?php

namespace Tests\Feature\Services\Pagination\Actions;

use App\Services\Pagination\Actions\PaginateArray;
use Tests\TestCase;

class PaginateArrayTest extends TestCase
{
    public function test()
    {
        $entries = [
            'test-1',
            'test-2',
            'test-3',
            'test-4',
        ];

        $paginator = app(PaginateArray::class)->execute($entries, 1, 2);

        $this->assertEquals(4, $paginator->total());

        $array = $paginator->toArray();

        $this->assertCount(2, $array['data']);
        $this->assertEquals('test-1', $array['data'][0]);
        $this->assertEquals('test-2', $array['data'][1]);

        $paginator = app(PaginateArray::class)->execute($entries, 2, 2);

        $this->assertEquals(4, $paginator->total());

        $array = $paginator->toArray();

        $this->assertCount(2, $array['data']);
        $this->assertEquals('test-3', $array['data'][0]);
        $this->assertEquals('test-4', $array['data'][1]);
    }
}
