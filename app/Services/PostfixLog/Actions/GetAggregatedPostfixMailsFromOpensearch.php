<?php

namespace App\Services\PostfixLog\Actions;

use App\Services\PostfixLog\Dtos\LogSearchDto;
use App\Services\PostfixLog\Enums\SearchableFieldsEnum;
use Carbon\Carbon;

class GetAggregatedPostfixMailsFromOpensearch
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
            $fields = [];

            foreach($logSearchDto->getFields() as $field) {
                $enum = SearchableFieldsEnum::from($field);

                $fields = array_merge($fields, $enum->getFields());
            }

            $query['bool']['filter'][] =  [
                "multi_match" => [
                    "query" => $logSearchDto->getSearch(),
                    'lenient' => true,
                    'type' => 'phrase_prefix',
                    'fields' => $fields,
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
