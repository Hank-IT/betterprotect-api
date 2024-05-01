<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Services\Transport\Actions\CreateTransport;
use App\Services\Transport\Actions\DeleteManyTransports;
use App\Services\Transport\Actions\PaginateTransports;
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

    public function store(Request $request, CreateTransport $createTransport)
    {
        $validator = Validator::make($request->all(), [
            'domain' => ['required', 'string', Rule::unique('transports')],
            'transport' => ['nullable', 'string'],
            'nexthop_type' => ['nullable', 'string', Rule::in(['ipv4,ipv6,hostname'])],
            'nexthop' => ['nullable', 'string'],
            'nexthop_port' => ['nullable', 'integer', 'max:65535', 'required_unless:nexthop_type,null'],
            'nexthop_mx' => ['nullable', 'boolean'],
            'data_source' => ['nullable', 'string'],
        ]);

        $validator->sometimes('nexthop', 'required', function ($input) {
            return $input->nexthope_type != null;
        });

        $validator->sometimes('nexthop', 'required|ipv4', function ($input) {
            return $input->nexthop_type === 'ipv4';
        });

        $validator->sometimes('nexthop', 'required|ipv6', function ($input) {
            return $input->nexthop_type === 'ipv6';
        });

        return new TransportResource(
            $createTransport->execute(
                $request->input('domain'),
                $request->input('transport'),
                $request->input('nexthop_type'),
                $request->input('nexthop'),
                $request->input('nexthop_port'),
                $request->input('nexthop_mx'),
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
