<?php

namespace App\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDataRetriever;
use App\Services\BetterprotectPolicy\Enums\PolicyDecisions;
use App\Services\BetterprotectPolicy\Repositories\ClientSenderAccessRepository;

class GetClientSenderAccess implements BetterprotectPolicyDataRetriever
{
    public function __construct(protected ClientSenderAccessRepository $clientSenderAccessRepository) {}

    public function execute(): array
    {
        return $this->clientSenderAccessRepository->get()->map(function ($row) {
            return collect($row->toArray())
                ->pipe(function($row) {
                    $row->put('action', strtolower($row->get('action')));

                    switch ($row->get('action')) {
                        case 'reject':
                            $row->put('action', $row->get('action') . ' ' . PolicyDecisions::POLICY_DENIED->value);
                            break;
                        case 'ok':
                            $row->put('action', $row->get('action') . ' ' . PolicyDecisions::POLICY_GRANTED->value);
                            break;
                    }

                    if ($row->get('message')) {
                        $row->put('action', $row->get('action') . ' - ' . $row->get('message'));
                    }

                    return $row;
                })
                ->only(['client_type', 'client_payload', 'sender_type', 'sender_payload', 'action', 'priority'])
                ->all();
        })->toArray();
    }
}
