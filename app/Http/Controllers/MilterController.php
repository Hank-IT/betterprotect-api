<?php

namespace App\Http\Controllers;

use App\Models\Milter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MilterController extends Controller
{
    public function index()
    {
        return Milter::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:milters',
            'definition' => 'required|string',
            'description' => 'nullable|string',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Milter wurde erfolgreich hinzugefügt.',
            'data' =>  Milter::create($request->all()),
        ], Response::HTTP_CREATED);
    }

    public function destroy(Milter $milter)
    {
        $milter->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Milter wurde erfolgreich entfernt.',
            'data' => [],
        ]);
    }
}
