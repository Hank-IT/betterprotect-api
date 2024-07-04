<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Services\RelayDomains\Actions\CreateRelayDomain;
use App\Services\RelayDomains\Actions\DeleteRelayDomain;
use App\Services\RelayDomains\Actions\PaginateRelayDomains;
use App\Services\RelayDomains\Models\RelayDomain;
use App\Services\RelayDomains\Resources\RelayDomainResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RelayDomainController extends Controller
{
    public function index(Request $request, PaginateRelayDomains $paginateRelayDomains)
    {
        $data = $request->validate([
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
            'search' => ['nullable', 'string'],
        ]);

        return RelayDomainResource::collection(
            $paginateRelayDomains->execute($data['page_number'], $data['page_size'], $data['search'] ?? null)
        );
    }

    public function store(Request $request, CreateRelayDomain $createRelayDomain)
    {
        $data = $request->validate([
            'domain' => ['required', 'string', Rule::unique('relay_domains')],
        ]);

        return new RelayDomainResource($createRelayDomain->execute($data['domain']));
    }

    public function destroy(DeleteRelayDomain $deleteRelayDomain, RelayDomain $relayDomain)
    {
        $deleteRelayDomain->execute($relayDomain);
    }
}
