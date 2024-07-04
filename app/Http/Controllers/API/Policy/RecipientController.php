<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Services\Recipients\Actions\FirstOrCreateRelayRecipient;
use App\Services\Recipients\Actions\DeleteRelayRecipient;
use App\Services\Recipients\Actions\PaginateRecipients;
use App\Services\Recipients\Models\RelayRecipient;
use App\Services\Recipients\Resources\RelayRecipientResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecipientController extends Controller
{
    public function index(Request $request, PaginateRecipients $paginateRecipients)
    {
        $data = $request->validate([
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
            'search' => ['nullable', 'string'],
        ]);

        return RelayRecipientResource::collection(
            $paginateRecipients->execute($data['page_number'], $data['page_size'], $data['search'] ?? null)
        );
    }

    public function store(Request $request, FirstOrCreateRelayRecipient $createRelayRecipient)
    {
        $data = $request->validate([
            'payload' => ['required', 'string', Rule::unique('relay_recipients')],
        ]);

        return new RelayRecipientResource(
            $createRelayRecipient->execute($data['payload'], 'local'),
        );
    }

    public function destroy(Request $request, DeleteRelayRecipient $deleteRelayRecipient)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', Rule::exists('relay_recipients', 'id')],
        ]);

        $models = RelayRecipient::find($data['ids']);

        // ToDo: Refactor this to delete all models using a single query
        foreach($models as $model) {
            $deleteRelayRecipient->execute($model);
        }

        return response(status: 200);
    }
}
