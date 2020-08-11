<?php

namespace App\Http\Requests;

use App\Support\IPv4;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class MilterStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_type' => 'required|string|in:client_ipv4,client_ipv6,client_ipv4_net',
            'client_payload' => 'required|string',
            'milter_id' => 'nullable|array',
            'milter_id.*' => 'integer|exists:milters,id',
        ];
    }

    /**
     * Apply some more conditional validations.
     */
    protected function passedValidation()
    {
        switch ($this->client_type) {
            case 'client_ipv4':
                if (filter_var($this->client_payload, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                    throw ValidationException::withMessages([
                        'client_payload' => 'Muss eine gültige IPv4 Adresse sein.'
                    ]);
                }
                break;
            case 'client_ipv6':
                if (filter_var($this->client_payload, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                    throw ValidationException::withMessages([
                        'client_payload' => 'Muss eine gültige IPv6 Adresse sein.'
                    ]);
                }
                break;
            case 'client_ipv4_net':
                if (! IPv4::isValidIPv4Net($this->client_payload)) {
                    throw ValidationException::withMessages([
                        'client_payload' => 'Muss ein gültiges IPv4 Netz sein.'
                    ]);
                }

                $bits = explode('/', $this->client_payload);
                if ($bits[1] < 24) {
                    throw ValidationException::withMessages([
                        'client_payload' => 'Das IPv4 Netz muss kleiner /24 sein.'
                    ]);
                }
                break;
        }
    }
}
