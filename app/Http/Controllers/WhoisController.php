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
        $info = (new Whois)->lookup($request->client_ip);

        if (isset($info['regrinfo']['owner']['organization'])) {
            $organization = $info['regrinfo']['owner']['organization'];
        } else {
            $organization = 'N/A';
        }

        if (isset($info['regrinfo']['abuse']['email'])) {
            $abuseEmail = $info['regrinfo']['abuse']['email'];
        } else {
            $abuseEmail = 'N/A';
        }

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
