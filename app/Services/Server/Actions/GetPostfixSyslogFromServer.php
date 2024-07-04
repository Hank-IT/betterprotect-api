<?php

namespace App\Services\Server\Actions;

use App\Services\Server\Factories\DatabaseFactory;
use App\Services\Server\Models\Server;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class GetPostfixSyslogFromServer
{
    public function __construct(protected DatabaseFactory $databaseFactory) {}

    public function execute(
        Server $server,
        Carbon $startDate,
        Carbon $endDate,
        int $pageNumber,
        int $pageSize,
        ?string $search = null
    ): LengthAwarePaginator {
        $connection = $this->databaseFactory->make(
            'log', $server->getDatabaseDetails('log')
        )->getConnection();

        $query = $connection->table('SystemEvents')
            ->select(['DeviceReportedTime', 'FromHost', 'Message', 'SysLogTag']);

        $startDate === $endDate
            ? $query->whereDate('DeviceReportedTime', '=', $startDate)
            : $query->whereBetween('DeviceReportedTime', [$startDate, $endDate]);

        if ($search) {
            $query->where('Message', 'LIKE', '%' . $search . '%');
        }

        $query->orderBy('DeviceReportedTime','desc');

        return $query->paginate($pageSize, ['*'], 'page', $pageNumber);
    }
}
