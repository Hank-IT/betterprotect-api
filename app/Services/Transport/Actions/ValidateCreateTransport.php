<?php

namespace App\Services\Transport\Actions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidatorInstance;

class ValidateCreateTransport
{
    public function execute(array $data): ValidatorInstance
    {
        $validator = Validator::make($data, [
            'domain' => ['required', 'string', Rule::unique('transports')],
            'transport' => ['nullable', 'string'],
            'nexthop_type' => ['nullable', 'string', Rule::in(['ipv4', 'ipv6', 'hostname'])],
            'nexthop' => ['nullable', 'string'],
            'nexthop_port' => ['nullable', 'integer', 'max:65535', 'required_unless:nexthop_type,null'],
            'nexthop_mx' => ['nullable', 'boolean'],
        ]);

        $validator->sometimes('nexthop', ['required'], function ($input) {
            return $input->nexthope_type != null;
        });

        $validator->sometimes('nexthop', ['required', 'ipv4'], function ($input) {
            return $input->nexthop_type === 'ipv4';
        });

        $validator->sometimes('nexthop', ['required', 'ipv6'], function ($input) {
            return $input->nexthop_type === 'ipv6';
        });

        return $validator;
    }
}
