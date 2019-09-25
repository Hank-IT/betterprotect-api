<?php

namespace App\Http\Controllers;

use App\Models\RelayDomain;
use App\Models\RelayRecipient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelayDomainController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        if ($request->filled('search')) {
            $relayRecipient = RelayRecipient::where('domain', 'LIKE', '%' . $request->search . '%');
        } else {
            $relayRecipient = RelayRecipient::query();
        }

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $relayRecipient->paginate($request->perPage, ['*'], 'page', $request->currentPage),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'domain' => 'required|string|unique:relay_domains',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Domäne wurde erfolgreich hinzugefügt.',
            'data' => RelayRecipient::create($request->all()),
        ], Response::HTTP_CREATED);
    }

    public function destroy(RelayDomain $relayDomain)
    {
        $relayDomain->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Domäne wurde erfolgreich entfernt.',
            'data' => [],
        ]);
    }
}
