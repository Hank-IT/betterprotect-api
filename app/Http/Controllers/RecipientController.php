<?php

namespace App\Http\Controllers;

use App\Services\Recipients\Models\RelayRecipient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecipientController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => ['nullable', 'string'],
            'currentPage' => ['required', 'int'],
            'perPage' => ['required', 'int'],
        ]);

        $recipients = $request->filled('search')
            ? RelayRecipient::where('payload', 'LIKE', '%' . $request->input('search') . '%')
            : RelayRecipient::query();

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $recipients->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage')),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
           'payload' => ['required', 'string', Rule::unique('relay_recipients')],
           'action' => ['required', 'string', Rule::in(['OK'])],
           'data_source' => ['required', 'string', Rule::in(['local'])],
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
