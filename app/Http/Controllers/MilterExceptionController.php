<?php

namespace App\Http\Controllers;

use App\Models\MilterException;
use App\Http\Requests\MilterStoreRequest;
use Symfony\Component\HttpFoundation\Response;

class MilterExceptionController extends Controller
{
    public function index()
    {
        return MilterException::orderBy('priority')->get();
    }

    public function store(MilterStoreRequest $request)
    {
        $exception = MilterException::create($request->all());

        $exception->milters()->sync($request->milter_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Milter Ausnahme wurde erfolgreich hinzugefügt.',
            'data' => [],
        ], Response::HTTP_CREATED);
    }

    public function destroy(MilterException $exception)
    {
        $exception->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Milter Ausnahme wurde erfolgreich entfernt.',
            'data' => [],
        ]);
    }
}
