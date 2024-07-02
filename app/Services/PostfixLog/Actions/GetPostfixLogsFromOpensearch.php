<?php

namespace App\Services\PostfixLog\Actions;

use App\Services\PostfixLog\Dtos\LogSearchDto;
use Carbon\Carbon;

class GetPostfixLogsFromOpensearch
{
    public function __construct(protected GetOpensearchClient $getOpensearchClient) {}

    public function execute(
        string  $index,
        Carbon  $start,
        Carbon  $end,
        int     $from,
        int     $size,
        ?LogSearchDto $logSearchDto,
    ): array
    {
        $client = $this->getOpensearchClient->execute();

        $query = [
            'bool' => [
                'filter' => [
                    [
                        'range' => [
                            'timestamp8601' => [
                                'gte' => $start,
                                'lte' => $end,
                            ]
                        ],
                    ],
                ],
            ],
        ];

        if ($logSearchDto) {
            $query['bool']['filter'][] =  [
                "multi_match" => [
                    "query" => $logSearchDto->getSearch(),
                    'lenient' => true,
                    'type' => 'phrase_prefix',
                    'fields' => $logSearchDto->getFields(),
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
                        'timestamp8601' => [
                            'order' => 'desc',
                        ]
                    ]
                ]
            ]
        ]);
    }
}
