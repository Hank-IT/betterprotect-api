<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RelayDomainController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
            'search' => ['nullable', 'string'],
        ]);


    }
}
