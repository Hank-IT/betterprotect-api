<?php

namespace App\Services\PostfixLog\Actions;

use Carbon\Carbon;

class GetRawPostfixLogsFromOpensearch
{
    public function __construct(protected GetOpensearchClient $getOpensearchClient) {}

    public function execute(
        string  $index,
        Carbon  $start,
        Carbon  $end,
        int     $from,
        int     $size,
        ?string $searchQuery,
    ): array
    {
        $client = $this->getOpensearchClient->execute();

        $query = [
            'bool' => [
                'filter' => [
                    [
                        'range' => [
                            '@timestamp' => [
                                'gte' => $start,
                                'lte' => $end,
                            ]
                        ],
                    ],
                ],
            ],
        ];

        if ($searchQuery) {
            $query['bool']['filter'][] =  [
                "multi_match" => [
                    "query" => $searchQuery,
                    'lenient' => true,
                    'type' => 'phrase_prefix',
                    'fields' => ['message'],
                ]
            ];
        }

        return $client->search([
            'index' => $index,
            'body' => [
                'from' => $from,
                'size' => $size,
                'query' => $query,
                'sort' => [
                    [
                        '@timestamp' => [
                            'order' => 'desc',
                        ]
                    ]
                ]
            ]
        ]);
    }
}
