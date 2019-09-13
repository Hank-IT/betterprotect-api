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
            'startDate' => 'required|date_format:Y-m-d\TH:i:s.v\Z|before:endDate',
            'endDate' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        $query = app(DatabasePostfixLog::class, [
            'servers' => Server::all(),
            'parameter' => ['startDate' => Carbon::parse($request->startDate), 'endDate' => Carbon::parse($request->endDate)]
        ]);

        if ($request->filled('search')) {
            $log = $query->search($request->search);
        } else {
            $log = $query->get();
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
