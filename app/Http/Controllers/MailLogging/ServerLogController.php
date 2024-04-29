<?php

namespace App\Http\Controllers\MailLogging;

use App\Http\Controllers\Controller;
use App\Services\MailLogging\Resources\OpensearchMailLogResource;
use DateTimeInterface;
use Illuminate\Http\Request;

class ServerLogController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'status' => 'nullable|string|in:reject,sent,deferred,bounced,filter',
            'startDate' => 'required|date_format:Y/m/d H:i|before:endDate',
            'endDate' => 'required|date_format:Y/m/d H:i',
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        $client = (new \OpenSearch\ClientBuilder())
            ->setHosts([env('OPENSEARCH_HOST')])
            ->setBasicAuthentication(env('OPENSEARCH_USER'), env('OPENSEARCH_PASSWORD'))
            ->setSSLVerification(false) // For testing only. Use certificate for validation
            ->build();

        $query = [
            'bool' => [
                'filter' => [
                    [
                        'match_all' => (object) [],
                    ],
                    [
                        'range' => [
                            'timestamp8601' => [
                                'time_zone' => config('app.timezone'),
                                'gte' => $request->date('startDate')->format(DateTimeInterface::ATOM),
                                'lte' => $request->date('endDate')->format(DateTimeInterface::ATOM),
                            ]
                        ],
                    ]
                ],
            ],
        ];

        $from = $request->integer('perPage') * ($request->integer('currentPage') - 1);

        $result = $client->search([
            'index' => env('OPENSEARCH_INDEX'),
            'body' => [
                'from' => $from,
                'size' => $request->integer('perPage'),
                'query' => $query
            ]
        ]);

        return [
            'total' => $result['hits']['total']['value'],
            'to' => $request->integer('perPage'),
            'from' => $from,
            'data' => OpensearchMailLogResource::collection($result['hits']['hits']),
        ];
    }
}
