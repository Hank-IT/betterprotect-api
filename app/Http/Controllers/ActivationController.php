<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivationController extends Controller
{
    public function store(Request $request, $id)
    {
        $this->validate($request, [
            'model' => 'required|string|in:ClientSenderAccess,RelayDomain,RelayRecipient,Transport',
        ]);

        app('App\\Models\\' . $request->model)->findOrFail($id)->activate();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktiviert.',
            'data' => [],
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'model' => 'required|string|in:ClientSenderAccess,RelayDomain,RelayRecipient,Transport',
        ]);

        app('App\\Models\\' . $request->model)->findOrFail($id)->deactivate();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich deaktiviert.',
            'data' => [],
        ]);
    }
}
