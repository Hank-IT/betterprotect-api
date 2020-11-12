<?php

namespace App\Postfix;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
//use App\Postfix\Parser\Parser;

class DatabasePostfixLog
{
    protected $servers;

    protected $parameter;

    public function __construct(Collection $servers, array $parameter)
    {
        $this->servers = $servers;

        $this->parameter = $parameter;
    }

    public function search(string $pattern, string $status = null)
    {
        $data = $this->get()->filter(function($a) use($pattern)  {
            return preg_grep('/' . $pattern . '/i', $a);
        });

        if (! is_null($status)) {
            return $data->where('status', $status);
        }

        return $data;
    }

    public function get(string $status = null)
    {
       $data = $this->servers->flatMap(function($server) use($status) {
           $logDB = $server->logDatabase()->getConnection();

           $query = $logDB->table('SystemEvents')
               ->select(['DeviceReportedTime', 'FromHost', 'Message', 'SysLogTag']);

           $this->parameter['startDate'] === $this->parameter['endDate']
               ? $query->whereDate('DeviceReportedTime', '=', $this->parameter['startDate'])
               : $query->whereBetween('DeviceReportedTime', [Carbon::parse($this->parameter['startDate']), Carbon::parse($this->parameter['endDate'])]);

           $start = microtime(true);
           Log::info('Server ' . $server->hostname . ' log query started');

           $queryResult = $query->orderBy('DeviceReportedTime','desc')->get()->toArray();

           $time = microtime(true) - $start;
           Log::info('Server ' . $server->hostname . ' log query finished. ' . $time . ' seconds.');

           $start = microtime(true);
           Log::info('Server ' . $server->hostname . ' log parsing started.');

           $data = app(Parser::class)->parse($queryResult);

           $time = microtime(true) - $start;
           Log::info('Server ' . $server->hostname . ' log parsing finished. ' . $time . ' seconds.');

           return $data;
       });

       if (! is_null($status)) {
           return $data->where('status', $status);
       }

       return $data->sortByDesc('reported_at');
    }
}
