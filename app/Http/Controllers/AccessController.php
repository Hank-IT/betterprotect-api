<?php

namespace App\Http\Controllers;

use App\Support\IPv4;
use Illuminate\Http\Request;
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
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        if ($request->filled('search')) {
            $clientSenderAccess = ClientSenderAccess::where('client', 'LIKE', '%' . $request->search . '%')->orwhere('sender', 'LIKE', '%' . $request->search . '%');
        } else {
            $clientSenderAccess = ClientSenderAccess::query();
        }

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $clientSenderAccess->paginate($request->perPage, ['*'], 'page', $request->currentPage),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'client' => 'string|required_unless,sender',
            'sender' => 'email|required_unless,client',
            'client_type' => 'required|string|hostname,ipv4,ipv4_net',
            'description' => 'string|nullable',
            'action' => 'required|string|in:ok,reject',
        ]);

        switch ($request->client_type) {
            case 'hostname': {
                //
                break;
            }
            case 'ipv4': {
                if (filter_var($request->payload, FILTER_VALIDATE_IP) === false) {
                    throw ValidationException::withMessages([
                        'payload' => 'Muss eine gültige IPv4 Adresse sein.'
                    ]);
                }
                break;
            }
            case 'ipv4_net': {
                if (! IPv4::isValidIPv4Net($request->payload)) {
                    throw ValidationException::withMessages([
                        'payload' => 'Muss ein gültiges IPv4 Netz sein.'
                    ]);
                }

                $bits = explode('/', $request->payload);
                if ($bits[1] < 24) {
                    throw ValidationException::withMessages([
                        'payload' => 'Das IPv4 Netz muss kleiner /24 sein.'
                    ]);
                }
                break;
            }
        }

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

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich entfernt.',
            'data' => [],
        ]);
    }
}
