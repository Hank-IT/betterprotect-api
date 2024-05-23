<?php

namespace Tests\Feature\Services\Transport\Commands;

use App\Services\Transport\Actions\DeleteTransportsByDataSource;
use Mockery\MockInterface;
use Tests\TestCase;

class CleanTransportRulesByDataSourceTest extends TestCase
{
    public function test()
    {
        $dataSource = 'testing';

        $this->mock(DeleteTransportsByDataSource::class, function(MockInterface $mock) use($dataSource) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $dataSource
            ]);
        });

        $this->artisan('transport:clean', ['data_source' => $dataSource])->assertSuccessful();
    }
}
