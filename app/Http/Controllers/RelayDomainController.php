<?php

namespace App\Http\Controllers;

use App\Models\RelayDomain;
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

        $relayRecipient = $request->filled('search')
            ? RelayDomain::where('domain', 'LIKE', '%' . $request->search . '%')
            : $relayRecipient = RelayDomain::query();

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
            'data' => RelayDomain::create($request->all()),
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
