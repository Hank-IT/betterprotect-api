<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RelayRecipient;

class RecipientController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        $recipients = $request->filled('search')
            ? RelayRecipient::where('payload', 'LIKE', '%' . $request->search . '%')
            : RelayRecipient::query();

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $recipients->paginate($request->perPage, ['*'], 'page', $request->currentPage),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
           'payload' => 'required|string|unique:relay_recipients',
           'action' => 'required|string|in:OK',
           'data_source' => 'required|string|in:local',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Empfänger wurde erfolgreich erstellt.',
            'data' => RelayRecipient::create($request->all()),
        ]);
    }

    public function destroy(RelayRecipient $access)
    {
        $access->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Empfänger wurde erfolgreich entfernt.',
            'data' => [],
        ]);
    }
}
