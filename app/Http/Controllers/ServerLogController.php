<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Postfix\DatabasePostfixLog;
use Illuminate\Pagination\LengthAwarePaginator;

class ServerLogController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'filter' => 'nullable|string|in:reject,sent,deferred,bounced',
            'startDate' => 'required|date_format:Y/m/d H:i|before:endDate',
            'endDate' => 'required|date_format:Y/m/d H:i',
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        $query = app(DatabasePostfixLog::class, [
            'servers' => Server::all(),
            'parameter' => ['startDate' => Carbon::parse($request->startDate), 'endDate' => Carbon::parse($request->endDate)]
        ]);

        if ($request->filled('search')) {
            $log = $query->search($request->search, $request->get('status'));
        } else {
            $log = $query->get($request->get('status'));
        }

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
