<?php

namespace App\Services\PostfixLog\Actions;

use OpenSearch\Client;
use OpenSearch\ClientBuilder;

class GetOpensearchClient
{
    public function __construct(protected ClientBuilder $builder) {}

    public function execute(): Client
    {
        return $this->builder
            ->setHosts([config('database.opensearch.default.host')])
            ->setBasicAuthentication(config('database.opensearch.default.username'), config('database.opensearch.default.password'))
            ->setSSLVerification(config('database.opensearch.default.ssl_verification'))
            ->build();
    }
}
