<?php

namespace App\Console\Commands;

use App\Models\Transport;
use Illuminate\Console\Command;

class CleanTransportRulesByMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transport:clean {meta}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all the transport rules with the specified meta attribute.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Transport::where('meta', '=', $this->argument('meta'))->delete();

        return true;
    }
}
