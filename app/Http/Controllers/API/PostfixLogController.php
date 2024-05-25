<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Pagination\Actions\PaginateArray;
use App\Services\PostfixLog\Actions\GetParsedPostfixLogsFromOpensearch;
use App\Services\PostfixLog\RefactorMeParser;
use App\Services\PostfixLog\Resources\PostfixLogRowResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostfixLogController extends Controller
{
    public function __invoke(
        Request $request,
        PaginateArray $paginateArray,
    ) {
        $data = $request->validate([
           // 'search' => ['nullable', 'string'],
           // 'status' => ['nullable', 'string', Rule::in(['reject', 'sent', 'deferred', 'bounced', 'filter'])],
            'start_date' => ['required', 'date', 'before:end_date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
        ]);

        $result = app(GetParsedPostfixLogsFromOpensearch::class)->execute(
            Carbon::parse($data['start_date']), Carbon::parse($data['end_date']), 0, 10000, $data['search'] ?? null
        );

        $parser = new RefactorMeParser;

        return PostfixLogRowResource::collection(
            $paginateArray->execute($parser->parse($result), $data['page_number'], $data['page_size'])
        );
    }
}
