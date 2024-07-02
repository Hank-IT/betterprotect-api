<?php

namespace App\Services\PostfixLog\Actions;

class GetPostfixMailFromOpensearch
{
    public function __construct(
        protected GetOpensearchClient $getOpensearchClient) {}

    public function execute(string $queueId): array {
        $client = $this->getOpensearchClient->execute();

        $query = [
            'match' => [
               'postfix_queueid' => $queueId,
            ],
        ];

        return $client->search([
            'index' => config('betterprotect.opensearch-postfix-parsed'),
            'body' => [
                'query' => $query,
                'sort' => [
                    [
                        'timestamp8601' => [
                            'order' => 'desc',
                        ]
                    ]
                ]
            ]
        ]);
    }
}
