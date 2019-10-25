<?php

namespace App\Console\Commands;

use App\Models\Transport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

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
    public function handle()
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

        $validator = Validator::make($data, [
            'domain' => 'required|string|unique:transports',
            'transport' => 'nullable|string',
            'nexthop_type' => 'nullable|string|in:ipv4,ipv6,hostname',
            'nexthop' => 'nullable|string',
            'nexthop_port' => 'nullable|integer|max:65535|required_unless:nexthop_type,null',
            'nexthop_mx' => 'nullable|boolean',
            'data_source' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $this->error('The provided data is invalid.');

            return false;
        }

        Transport::create($data);

        return true;
    }
}
