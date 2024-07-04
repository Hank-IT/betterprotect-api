<?php

namespace Tests\Feature\Services\Recipients\Actions;

use App\Services\Recipients\Actions\PruneObsoleteRecipientsForDataSource;
use App\Services\Recipients\Models\RelayRecipient;
use Tests\TestCase;

class PruneObsoleteRecipientsForDataSourceTest extends TestCase
{
    public function test()
    {
        $dataSource = 'testing';

        $recipientInDatabaseShouldBeRetained = RelayRecipient::factory()->create([
            'data_source' => $dataSource
        ]);

        $recipientInDatabaseShouldBeRemoved = RelayRecipient::factory()->create([
            'data_source' => $dataSource
        ]);

        $unrelatedRecipient = RelayRecipient::factory()->create([
            'data_source' => 'other',
        ]);

        app(PruneObsoleteRecipientsForDataSource::class)->execute([
            $recipientInDatabaseShouldBeRetained->payload,
            ], $dataSource);

        $this->assertModelExists($recipientInDatabaseShouldBeRetained);
        $this->assertModelExists($unrelatedRecipient);

        $this->assertDatabaseMissing('relay_recipients', [
            'payload' => $recipientInDatabaseShouldBeRemoved->payload,
            'data_source' => $dataSource,
        ]);
    }
}
