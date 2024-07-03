<?php

namespace App\Http\Controllers\API\Logging;

use App\Http\Controllers\Controller;
use App\Services\PostfixLog\Actions\GetAndConvertAggregatedPostfixMailsFromOpensearch;
use App\Services\PostfixLog\Actions\GetAggregatedPostfixMailFromOpensearch;
use App\Services\PostfixLog\Dtos\LogSearchDto;
use App\Services\PostfixLog\Dtos\PostfixMail;
use App\Services\PostfixLog\Enums\SearchableFieldsEnum;
use App\Services\PostfixLog\Resources\PostfixMailCollection;
use App\Services\PostfixLog\Resources\PostfixMailResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AggregatedLogController extends Controller
{
    public function index(Request $request) {
        // ToDo: Only output minimal information here

        $data = $request->validate([
            'search' => ['nullable', 'string'],
            'search_fields' => ['required', 'array'],
            'search_fields.*' => ['required', Rule::enum(SearchableFieldsEnum::class)],
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'timezone' => ['timezone', 'required', 'string'],
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
        ]);

        $dto = isset($data['search']) ? new LogSearchDto($data['search'], $data['search_fields']): null;

        $result = app(GetAndConvertAggregatedPostfixMailsFromOpensearch::class)->execute(
            Carbon::parse($data['start_date'], $data['timezone'])->setTime(0, 0),
            Carbon::parse($data['end_date'], $data['timezone'])->setTime(23, 59, 59),
            ($data['page_number'] - 1) * $data['page_size'],
            $data['page_size'],
            $dto,
        );

        return new PostfixMailCollection($result->getConvertedResults(), $result->getHits(), $result->getRelation());
    }

    public function show(string $queueId) {
        $result = app(GetAggregatedPostfixMailFromOpensearch::class)->execute($queueId);

        return new PostfixMailResource(
            new PostfixMail($result['hits']['hits'][0]['_source'])
        );
    }
}
