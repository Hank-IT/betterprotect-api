<?php

namespace App\Http\Controllers;

use App\Support\IPv4;
use Illuminate\Http\Request;
use App\Services\AccessOrder;
use App\Models\ClientSenderAccess;
use App\Services\Access as AccessService;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AccessController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
        ]);

        if ($request->filled('search')) {
            $clientSenderAccess = ClientSenderAccess::where('client_payload', 'LIKE', '%' . $request->search . '%')
                ->orWhere('sender_payload', 'LIKE', '%' . $request->search . '%');
        } else {
            $clientSenderAccess = ClientSenderAccess::query();
        }

        $clientSenderAccess->orderBy('priority');

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $clientSenderAccess->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'client_type' => 'required|string|in:*,client_reverse_hostname,client_hostname,client_ipv4,client_ipv6,client_ipv4_net',
            'client_payload' => 'required|string',
            'sender_type' => 'required|in:*,mail_from_address,mail_from_domain,mail_from_localpart',
            'sender_payload' => 'required|string',
            'description' => 'string|nullable',
            'action' => 'required|string|in:ok,reject'
        ]);

        switch ($request->client_type) {
            case 'client_ipv4': {
                if (filter_var($request->client_payload, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                    throw ValidationException::withMessages([
                        'payload' => 'Muss eine gültige IPv4 Adresse sein.'
                    ]);
                }
                break;
            }
            case 'client_ipv6': {
                if (filter_var($request->client_payload, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                    throw ValidationException::withMessages([
                        'payload' => 'Muss eine gültige IPv6 Adresse sein.'
                    ]);
                }
                break;
            }
            case 'client_ipv4_net': {
                if (! IPv4::isValidIPv4Net($request->client_payload)) {
                    throw ValidationException::withMessages([
                        'payload' => 'Muss ein gültiges IPv4 Netz sein.'
                    ]);
                }

                $bits = explode('/', $request->client_payload);
                if ($bits[1] < 24) {
                    throw ValidationException::withMessages([
                        'payload' => 'Das IPv4 Netz muss kleiner /24 sein.'
                    ]);
                }
                break;
            }
        }

        switch ($request->sender_type) {
            case 'mail_from_address': {
                if (filter_var($request->sender_payload, FILTER_VALIDATE_EMAIL) === false) {
                    throw ValidationException::withMessages([
                        'payload' => 'Muss eine gültige E-Mail Adresse sein.'
                    ]);
                }
                break;
            }
        };

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich hinzugefügt.',
            'data' => app(AccessService::class)->store($request->all()),
        ], Response::HTTP_CREATED);
    }

    public function show(ClientSenderAccess $access)
    {
        return $access;
    }

    public function destroy(ClientSenderAccess $access)
    {
        $access->delete();

        AccessOrder::reOrder();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich entfernt.',
            'data' => [],
        ]);
    }
}
