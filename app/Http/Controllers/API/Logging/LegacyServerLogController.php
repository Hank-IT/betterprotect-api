<?php

namespace App\Http\Controllers\API\Logging;

use App\Http\Controllers\Controller;
use App\Services\PostfixLog\LegacyPostfixParser\DatabasePostfixLog;
use App\Services\Server\Models\Server;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LegacyServerLogController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'status' => 'nullable|string|in:reject,sent,deferred,bounced,filter',
            'startDate' => 'required|date_format:Y/m/d H:i|before:endDate',
            'endDate' => 'required|date_format:Y/m/d H:i',
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        $query = app(DatabasePostfixLog::class, [
            'servers' => Server::where('log_feature_enabled', '=', true)->get(),
            'parameter' => ['startDate' => Carbon::parse($request->startDate), 'endDate' => Carbon::parse($request->endDate)]
        ]);

        $log = $request->filled('search')
            ? $query->search($request->search, $request->get('status'))
            : $query->get($request->get('status'));

        // Paginate
        $count = $log->count();
        $offset = ($request->currentPage-1) * $request->perPage;
        $log = array_slice($log->toArray(), $offset, $request->perPage);
        $log = new LengthAwarePaginator($log, $count, $request->perPage, $request->currentPage);

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $log,
        ]);
    }
}
