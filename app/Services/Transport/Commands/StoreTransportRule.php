<?php

namespace App\Services\Transport\Commands;

use App\Services\Transport\Actions\CreateTransport;
use App\Services\Transport\Actions\ValidateCreateTransport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreTransportRule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transport:store {domain} {--transport=} {--nexthop=} {--nexthop_type=} {--nexthop_mx=} {--nexthop_port=} {--data_source=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store a Postfix transport rule.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CreateTransport $createTransport, ValidateCreateTransport $validateCreateTransport)
    {
        $data = [
            'domain' => $this->argument('domain'),
            'transport' => $this->option('transport'),
            'nexthop' => $this->option('nexthop'),
            'nexthop_type' => $this->option('nexthop_type'),
            'nexthop_mx' => $this->option('nexthop_mx'),
            'nexthop_port' => $this->option('nexthop_port'),
            'data_source' => $this->option('data_source'),
        ];

        $validator = $validateCreateTransport->execute($data);

        if ($validator->fails()) {
            $this->error(sprintf('The provided data is invalid. Errors: %s', json_encode($validator->errors())));

            return 1;
        }

        $createTransport->execute(
            $data['domain'],
            $data['transport'],
            $data['nexthop_type'],
            $data['nexthop'],
            $data['nexthop_port'],
            $data['nexthop_mx'],
            $data['data_source'],
        );

        return 0;
    }
}
