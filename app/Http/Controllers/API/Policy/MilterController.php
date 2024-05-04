<?php

namespace App\Http\Controllers\API\Policy;
use App\Http\Controllers\Controller;
use App\Services\Milter\Actions\CreateMilter;
use App\Services\Milter\Actions\DeleteMilter;
use App\Services\Milter\Models\Milter;
use App\Services\Milter\Resources\MilterResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MilterController extends Controller
{
    public function index()
    {
        return MilterResource::collection(Milter::all());
    }

    public function store(Request $request, CreateMilter $createMilter)
    {
        $data = $request->validate([
            'name' => ['required', 'string', Rule::unique('milters')],
            'definition' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        return new MilterResource($createMilter->execute(
            $data['name'],
            $data['definition'],
            $data['description']
        ));
    }

    public function destroy(DeleteMilter $deleteMilter, Milter $milter)
    {
        $deleteMilter->execute($milter);

        return response(status: 200);
    }
}
