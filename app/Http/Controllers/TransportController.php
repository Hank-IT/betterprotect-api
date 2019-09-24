<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransportController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        if ($request->filled('search')) {
            $transport = Transport::where('domain', 'LIKE', '%' . $request->search . '%');
        } else {
            $transport = Transport::query();
        }

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $transport->paginate($request->perPage, ['*'], 'page', $request->currentPage),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string|unique:transports',
            'transport' => 'nullable|string',
            'nexthop_type' => 'nullable|string|in:ipv4,ipv6,hostname',
            'nexthop' => 'nullable|string',
            'nexthop_port' => 'nullable|integer|max:65535|required_unless:nexthop_type,null',
            'nexthop_mx' => 'nullable|boolean',
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

        $validator->validate();

        return response()->json([
            'status' => 'success',
            'message' => 'Transport wurde erfolgreich erstellt.',
            'data' => Transport::create($request->all()),
        ]);
    }

    public function destroy(Request $request, Transport $transport)
    {
        $transport->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transport wurde erfolgreich entfernt.',
            'data' => [],
        ]);
    }

    public function show(Request $request, Transport $transport)
    {

    }
}
