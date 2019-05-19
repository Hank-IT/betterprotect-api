<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Server;
use App\Services\PostfixLog;
use Illuminate\Http\Request;
use App\Services\ServerDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

class ServerLogController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'startDate' => 'required|date_format:Y-m-d',
            'endDate' => 'required|date_format:Y-m-d',
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        $servers = Server::all();

        $logs = $servers->flatMap(function($server) use ($request) {
            $logDB = (new ServerDatabase($server))->getLogConnection();

            $query = $logDB->table('SystemEvents')
                ->select(['DeviceReportedTime', 'FromHost', 'Message', 'SysLogTag']);

            if ($request->startDate === $request->endDate) {
                $query->whereDate('DeviceReportedTime', '=', $request->startDate);
            } else {
                $query->whereBetween('DeviceReportedTime', [Carbon::parse($request->startDate), Carbon::parse($request->endDate)]);
            }

            $data = $query->orderBy('DeviceReportedTime','desc')->get();

            $data = app(PostfixLog::class)->parse($data->toArray());

            if ($request->filled('search')) {
                $pattern = $request->search;
                $data = array_filter($data, function($a) use($pattern)  {
                    return preg_grep('/' . $pattern . '/i', $a);
                });
            }

            return $data;
        });

        // Paginate
        $count = $logs->count();
        $offset = ($request->currentPage-1) * $request->perPage;
        $logs = array_slice($logs->toArray(), $offset, $request->perPage);
        $logs = new LengthAwarePaginator($logs, $count, $request->perPage, $request->currentPage);

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $logs,
        ]);
    }
}
