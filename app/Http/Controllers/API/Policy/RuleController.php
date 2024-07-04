<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Services\Rules\Actions\CreateClientSenderAccess;
use App\Services\Rules\Actions\DeleteClientSenderAccess;
use App\Services\Rules\Actions\GetClientSenderAccess;
use App\Services\Rules\Actions\ValidateClient;
use App\Services\Rules\Actions\ValidateSender;
use App\Services\Rules\Models\ClientSenderAccess;
use App\Services\Rules\Resources\ClientSenderAccessResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RuleController extends Controller
{
    public function index(Request $request, GetClientSenderAccess $getClientSenderAccess)
    {
        $data = $request->validate([
            'search' => ['nullable', 'string'],
        ]);

        return ClientSenderAccessResource::collection(
            $getClientSenderAccess->execute($data['search'] ?? null),
        );
    }

    public function store(
        Request $request,
        ValidateClient $validateClient,
        ValidateSender $validateSender,
        CreateClientSenderAccess $createClientSenderAccess,
    ) {
        $data = $request->validate([
            'client_type' => ['required', 'string', Rule::in(['*', 'client_reverse_hostname', 'client_hostname', 'client_ipv4', 'client_ipv6', 'client_ipv4_net'])],
            'client_payload' => ['required', 'string'],
            'sender_type' => ['required', 'string', Rule::in(['*', 'mail_from_address', 'mail_from_domain', 'mail_from_localpart'])],
            'sender_payload' => ['required', 'string'],
            'message' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'action' => ['required', 'string', Rule::in(['ok', 'reject'])],
        ]);

        $validateClient->execute($data['client_type'], $data['client_payload']);

        $validateSender->execute($data['sender_type'], $data['sender_payload']);

        $model = $createClientSenderAccess->execute(
            $data['client_type'],
            $data['client_payload'],
            $data['sender_type'],
            $data['sender_payload'],
            $data['action'],
            $data['message'] ?? null,
            $data['description'] ?? null,
        );

        return new ClientSenderAccessResource($model);
    }

    public function destroy(DeleteClientSenderAccess $deleteClientSenderAccess, ClientSenderAccess $clientSenderAccess)
    {
        $deleteClientSenderAccess->execute($clientSenderAccess);

        return response(status: 200);
    }
}
