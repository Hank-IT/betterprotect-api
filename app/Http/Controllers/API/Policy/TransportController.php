<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Services\Transport\Actions\CreateTransport;
use App\Services\Transport\Actions\DeleteManyTransports;
use App\Services\Transport\Actions\PaginateTransports;
use App\Services\Transport\Actions\ValidateCreateTransport;
use App\Services\Transport\Resources\TransportResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TransportController extends Controller
{
    public function index(Request $request, PaginateTransports $paginateTransports)
    {
        $data = $request->validate([
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
            'search' => ['nullable', 'string'],
        ]);

        return TransportResource::collection(
            $paginateTransports->execute($data['page_number'], $data['page_size'], $data['search'] ?? null)
        );
    }

    public function store(
        Request $request, CreateTransport $createTransport, ValidateCreateTransport $validateCreateTransport
    ) {
        $data = $validateCreateTransport->execute($request->all())->validate();

        return new TransportResource(
            $createTransport->execute(
                $data['domain'],
                $data['transport'],
                $data['nexthop_type'],
                $data['nexthop'],
                $data['nexthop_port'],
                $data['nexthop_mx'],
                'local',
            )
        );
    }

    public function destroy(Request $request, DeleteManyTransports $deleteManyTransports)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', Rule::exists('transports', 'id')],
        ]);

        $deleteManyTransports->execute($data['ids']);

        return response(status: 200);
    }
}
