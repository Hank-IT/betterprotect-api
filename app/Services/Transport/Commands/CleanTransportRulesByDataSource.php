<?php

namespace App\Services\Transport\Commands;

use App\Services\Transport\Models\Transport;
use Illuminate\Console\Command;

class CleanTransportRulesByDataSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transport:clean {data_source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all the transport rules with the specified data source.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Transport::where('data_source', '=', $this->argument('data_source'))->delete();

        return true;
    }
}
