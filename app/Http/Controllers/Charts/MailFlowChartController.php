<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;
use App\Services\Charts\MailFlowChart;
use App\Services\PostfixLog\LegacyPostfixParser\DatabasePostfixLog;
use App\Services\Server\Models\Server;
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
