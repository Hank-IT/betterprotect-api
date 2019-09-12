<?php

namespace App\Postfix;

use Carbon\Carbon;
use App\Services\ServerDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DatabasePostfixLog extends PostfixLog
{
    protected $servers;

    protected $parameter;

    public function __construct(Collection $servers, array $parameter)
    {
        $this->servers = $servers;

        $this->parameter = $parameter;
    }

    public function get()
    {
       return $this->servers->flatMap(function($server) {
            $logDB = (new ServerDatabase($server))->getLogConnection();

           $query = $logDB->table('SystemEvents')
               ->select(['DeviceReportedTime', 'FromHost', 'Message', 'SysLogTag']);

           if ($this->parameter['startDate'] === $this->parameter['endDate']) {
               $query->whereDate('DeviceReportedTime', '=', $this->parameter['startDate']);
           } else {
               $query->whereBetween('DeviceReportedTime', [Carbon::parse($this->parameter['startDate']), Carbon::parse($this->parameter['endDate'])]);
           }

           $start = microtime(true);
           Log::info('Server ' . $server->hostname . ' log query started');

           $queryResult = $query->orderBy('DeviceReportedTime','desc')->get();

           $time = microtime(true) - $start;
           Log::info('Server ' . $server->hostname . ' log query finished. ' . $time . ' seconds.');

           $start = microtime(true);
           Log::info('Server ' . $server->hostname . ' log parsing started.');

           $data = app(Parser::class)->parse($queryResult->toArray());

           $time = microtime(true) - $start;
           Log::info('Server ' . $server->hostname . ' log parsing finished. ' . $time . ' seconds.');

           return $data;
       });
    }
}
