<?php

namespace App\Jobs;

use App\Models\Transport;
use Carbon\Carbon;
use App\Support\IPv4;
use App\Models\Server;
use App\Services\ViewTask;
use Illuminate\Bus\Queueable;
use App\Models\RelayRecipient;
use App\Exceptions\ErrorException;
use App\Models\ClientSenderAccess;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\ServerDatabase as ServerDatabaseService;

class PolicyInstallation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Server Server
     */
    protected $server;

    /**
     * @var Authenticatable
     */
    protected $user;


    /**
     * @var DatabaseManager
     */
    protected $dbConnection;

    public function __construct(Server $server, Authenticatable $user)
    {
        $this->server = $server;

        $this->user = $user;
    }

    public function handle()
    {
        $db = app(ServerDatabaseService::class, ['server' => $this->server]);

        if ($db->needsMigrate()) {
            throw new ErrorException;
        }

        $this->dbConnection = $db->getPolicyConnection();

        $viewTask = app(ViewTask::class, [
            'message' => 'Policy wird auf Server ' . $this->server->hostname . ' installiert...',
            'task' => 'install-policy',
            'username' => $this->user->username,
        ]);

        $this->insertClientAccess();

        $this->insertSenderAccess();

        $this->insertRecipientAccess();

        $this->insertTransportMaps();

        $viewTask->finishedSuccess('Policy erfolgreich auf Server ' . $this->server->hostname . ' installiert.');

        $this->server->last_policy_install = Carbon::now();

        $this->server->save();
    }

    protected function insertClientAccess()
    {
        $clientAccess = ClientSenderAccess::whereIn('type', ['client_hostname', 'client_ipv4'])->get();

        // Generate client access ip network range
        $clientAccessNets = ClientSenderAccess::where('type', '=', 'client_ipv4_net')->get();

        $clientAccessIps = [];
        foreach ($clientAccessNets as $key => $clientAccessNet) {

            $clientAccessIps[$key] = IPv4::cidr2range($clientAccessNet->payload);

            foreach($clientAccessIps[$key] as $index => $data) {
                $clientAccessIps[$index]['payload'] = $data;
                $clientAccessIps[$index]['action'] = $clientAccessNet->action;
                unset($clientAccessIps[$key]);
            }
        }

        $clientAccessIps = array_values($clientAccessIps);

        $data = $clientAccess->map(function ($row) {
            return collect($row->toArray())
                ->only(['payload', 'action'])
                ->all();
        });

        $this->dbConnection->beginTransaction();

        $this->dbConnection->getPdo()->exec('lock tables client_access write');

        $this->dbConnection->table('client_access')->truncate();

        $this->dbConnection->table('client_access')->insert($data->toArray());

        $this->dbConnection->table('client_access')->insert($clientAccessIps);

        $this->dbConnection->getPdo()->exec('unlock tables');

        $this->dbConnection->commit();
    }

    protected function insertSenderAccess()
    {
        $senderAccess = ClientSenderAccess::whereIn('type', ['mail_from_address', 'mail_from_domain', 'mail_from_localpart'])->get();

        $data = $senderAccess->map(function ($row) {
            return collect($row->toArray())
                ->only(['payload', 'action'])
                ->all();
        });

        $this->dbConnection->beginTransaction();

        $this->dbConnection->getPdo()->exec('lock tables sender_access write');

        $this->dbConnection->table('sender_access')->truncate();

        $this->dbConnection->table('sender_access')->insert($data->toArray());

        $this->dbConnection->getPdo()->exec('unlock tables');

        $this->dbConnection->commit();
    }

    protected function insertRecipientAccess()
    {
        $recipientAccess = RelayRecipient::all();

        $data = $recipientAccess->map(function ($row) {
            return collect($row->toArray())
                ->only(['payload', 'action'])
                ->all();
        });

        $this->dbConnection->beginTransaction();

        $this->dbConnection->getPdo()->exec('lock tables relay_recipients write');

        $this->dbConnection->table('relay_recipients')->truncate();

        $this->dbConnection->table('relay_recipients')->insert($data->toArray());

        $this->dbConnection->getPdo()->exec('unlock tables');

        $this->dbConnection->commit();
    }

    protected function insertTransportMaps()
    {
        $transportMaps = Transport::all();

        $data = $transportMaps->map(function ($row) {
            if ($row->nexthop_type == 'ipv4' || $row->nexthop_type == 'ipv6') {
                $nexthop = '[' . $row->nexthop . ']';
            } else {
                if ($row->nexthop_mx) {
                    $nexthop = $row->nexthop;
                } else {
                    $nexthop = '[' . $row->nexthop . ']';
                }
            }

            return collect([
                'domain' => $row->domain,
                'payload' => $row->transport . ':' . $nexthop . ':' . $row->nexthop_port,
            ]);
        });

        $this->dbConnection->beginTransaction();

        $this->dbConnection->getPdo()->exec('lock tables transport_maps write');

        $this->dbConnection->table('transport_maps')->truncate();

        $this->dbConnection->table('transport_maps')->insert($data->toArray());

        $this->dbConnection->getPdo()->exec('unlock tables');

        $this->dbConnection->commit();
    }
}
