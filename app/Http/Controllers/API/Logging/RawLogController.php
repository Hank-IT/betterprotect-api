<?php

namespace App\Http\Controllers\API\Logging;

use App\Http\Controllers\Controller;
use App\Services\PostfixLog\Actions\GetRawPostfixLogsFromOpensearch;
use App\Services\PostfixLog\Dtos\PostfixRawLogRow;
use App\Services\PostfixLog\Resources\PostfixRawLogRowCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RawLogController extends Controller
{
    public function index(Request $request) {
        $data = $request->validate([
            'search' => ['nullable', 'string'],
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'timezone' => ['timezone', 'required', 'string'],
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
        ]);

        $result = app(GetRawPostfixLogsFromOpensearch::class)->execute(
            config('betterprotect.opensearch-postfix-archive'),
            Carbon::parse($data['start_date'], $data['timezone'])->setTime(0, 0),
            Carbon::parse($data['end_date'], $data['timezone'])->setTime(23, 59, 59),
            ($data['page_number'] - 1) * $data['page_size'],
            $data['page_size'],
            $data['search'] ?? null,
        );

        $objs = [];
        foreach($result['hits']['hits'] as $hit) {
            $objs[] = new PostfixRawLogRow($hit['_source']);
        }

        return new PostfixRawLogRowCollection(
            $objs, $result['hits']['total']['value'], $result['hits']['total']['relation']
        );
    }
}
