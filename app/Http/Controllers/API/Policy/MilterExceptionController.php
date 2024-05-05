<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Services\Milter\Actions\CreateMilterException;
use App\Services\Milter\Actions\CreateMilterExceptionForMilter;
use App\Services\Milter\Actions\DeleteMilterException;
use App\Services\Milter\Actions\SyncMilterExceptionsWithMilters;
use App\Services\Milter\Actions\ValidateMilterExceptionClient;
use App\Services\Milter\Models\MilterException;
use App\Services\Milter\Resources\MilterExceptionResource;
use App\Services\Order\Actions\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MilterExceptionController extends Controller
{
    public function index()
    {
        return MilterExceptionResource::collection(
            MilterException::orderBy('priority')->with(['milters'])->get(),
        );
    }

    public function store(
        Request                         $request,
        ValidateMilterExceptionClient   $validateMilterExceptionClient,
        CreateMilterExceptionForMilter $createMilterExceptionForMilter,
    ) {
        $data = $request->validate([
            'client_type' => ['required', 'string', Rule::in(['client_ipv4', 'client_ipv6', 'client_ipv4_net'])],
            'client_payload' => ['required', 'string'],
            'milter_id' => ['nullable', 'array'],
            'milter_id.*' => ['integer', Rule::exists('milters', 'id')],
            'description' => ['nullable', 'string'],
        ]);

        $validateMilterExceptionClient->execute($data['client_type'], $data['client_payload']);

        $model = $createMilterExceptionForMilter->execute(
            $data['client_type'],
            $data['client_payload'],
            $data['description'],
            $data['milter_id'] ?? [],
        );

        return new MilterExceptionResource($model);
    }

    public function destroy(DeleteMilterException $deleteMilterException, MilterException $milterException)
    {
        $deleteMilterException->execute($milterException);

        app(OrderItems::class, ['model' => $milterException])->reOrder();

        return response(status: 200);
    }
}
