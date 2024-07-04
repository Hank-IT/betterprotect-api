<?php

namespace Tests\Feature\Services\Transport\Actions;

use App\Services\Transport\Actions\DeleteTransportsByDataSource;
use App\Services\Transport\Models\Transport;
use Tests\TestCase;

class DeleteTransportsByDataSourceTest extends TestCase
{
    public function test()
    {
        $deletable = Transport::factory()->create([
            'data_source' => 'testing',
        ]);

        $retainable = Transport::factory()->create([
            'data_source' => 'other',
        ]);

        app(DeleteTransportsByDataSource::class)->execute('testing');

        $this->assertModelMissing($deletable);
        $this->assertModelExists($retainable);
    }
}
