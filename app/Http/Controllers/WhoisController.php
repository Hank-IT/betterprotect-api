<?php

namespace App\Http\Controllers;

use phpWhois\Whois;
use Illuminate\Http\Request;

class WhoisController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'client_ip' => 'required|string|ip'
        ]);

        // Whois
        $info = app(Whois::class)->lookup($request->client_ip);

        $organization = isset($info['regrinfo']['owner']['organization'])
            ? $info['regrinfo']['owner']['organization']
            : 'N/A';

        $abuseEmail = isset($info['regrinfo']['abuse']['email'])
            ? $info['regrinfo']['abuse']['email']
            : 'N/A';

        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => [
                'organization' => $organization,
                'abuse' => $abuseEmail,
            ],
        ]);
    }
}
