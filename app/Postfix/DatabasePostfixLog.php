<?php

namespace App\Postfix;

use Carbon\Carbon;
use App\Services\ServerDatabase;
use Illuminate\Support\Collection;

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

            return app(Parser::class)->parse(
                $query->orderBy('DeviceReportedTime','desc')->get()->toArray()
            );
        });
    }
}
