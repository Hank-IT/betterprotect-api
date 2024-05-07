<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Services\Activation\Enums\ActivatableEntitiesEnum;
use Illuminate\Http\Request;

class ActivatableController extends Controller
{
    public function __invoke(Request $request, ActivatableEntitiesEnum $activatableEntitiesEnum, int $id)
    {
        $data = $request->validate([
            'state' => ['required', 'boolean'],
        ]);

        $activatableEntitiesEnum->find($id)->setActive($data['state']);

        return response(status: 200);
    }
}
