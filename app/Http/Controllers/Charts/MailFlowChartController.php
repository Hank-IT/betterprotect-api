<?php

namespace App\Http\Controllers\Charts;

use App\Charts\MailFlowChart;
use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\MailLogging\LegacyPostfixParser\DatabasePostfixLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MailFlowChartController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'startDate' => ['required', 'date_format:Y/m/d H:i', 'before:endDate'],
            'endDate' => ['required', 'date_format:Y/m/d H:i'],
        ]);

        // Pull and parse logs for all enabled servers
        $query = app(DatabasePostfixLog::class, [
            'servers' => Server::where('log_feature_enabled', '=', true)->get(),
            'parameter' => ['startDate' => $request->startDate, 'endDate' => $request->endDate]]);

        return app(MailFlowChart::class, ['data' => $query->get(), 'start' => Carbon::parse($request->startDate), 'end' => Carbon::parse($request->endDate)])->build();
    }
}
