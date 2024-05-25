<?php

namespace App\Services\PostfixLog\Actions;

use Carbon\Carbon;

class GetPostfixLogsFromOpensearch
{
    public function __construct(protected GetOpensearchClient $getOpensearchClient)
    {
    }

    public function execute(
        string  $index,
        Carbon  $start,
        Carbon  $end,
        int     $from,
        int     $size,
        ?string $search,
    ): array
    {
        $client = $this->getOpensearchClient->execute();

        $query = [
            'bool' => [
                'filter' => [
                    [
                        'range' => [
                            'timestamp8601' => [
                                'time_zone' => config('app.timezone'),
                                'gte' => $start,
                                'lte' => $end,
                            ]
                        ],
                    ],
                ],
            ],
        ];

        if ($search) {
            $query['bool']['filter'][] =  [
                "match" => [
                    "message" => $search,
                ]
            ];
        }

        return $client->search([
            'index' => $index,
            'body' => [
                'from' => $from,
                'size' => $size,
                'query' => $query,
            ]
        ]);
    }
}
