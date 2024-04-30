<?php

namespace Database\Factories;

use App\Exceptions\ErrorException;
use App\Services\Rules\Models\ClientSenderAccess;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientSenderAccessFactory extends Factory
{
    protected $model = ClientSenderAccess::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $clientTypes = ['*', 'client_reverse_hostname', 'client_hostname', 'client_ipv4', 'client_ipv6', 'client_ipv4_net'];
        $clientType = $clientTypes[array_rand($clientTypes)];

        $senderTypes = ['*', 'mail_from_address', 'mail_from_domain', 'mail_from_localpart'];
        $senderType = $senderTypes[array_rand($senderTypes)];

        return [
            'client_type' => $clientType,
            'client_payload' => $this->getClientPayload($clientType),
            'sender_type' => $senderType,
            'sender_payload' => $this->getSenderPayload($senderType),
            'message' => ['', fake()->text][fake()->numberBetween(0, 1)],
            'description' =>  ['', fake()->text][fake()->numberBetween(0, 1)],
            'action' =>  ['ok', 'reject'][fake()->numberBetween(0, 1)],
        ];
    }

    protected function getClientPayload(string $clientType): string
    {
        $items = [
            fake()->ipv4,
            fake()->ipv6,
            '192.168.0.0/24',
        ];

        switch ($clientType) {
            case '*':
                return $items[array_rand($items)];
            case 'client_hostname':
            case 'client_reverse_hostname':
                return fake()->domainName;
            case 'client_ipv6':
            case 'client_ipv4':
                return fake()->ipv4;
            case 'client_ipv4_net':
                return '192.168.0.0/24';
        }

        throw new ErrorException('Unknown client_type: ' . $clientType);
    }

    protected function getSenderPayload(string $senderType): string
    {
        $items = [
            fake()->email,
            fake()->domainName,
            fake()->userName,
        ];

        switch ($senderType) {
            case '*':
                return $items[array_rand($items)];
            case 'mail_from_address':
                return fake()->email;
            case 'mail_from_domain':
                return fake()->domainName;
            case 'mail_from_localpart':
                return fake()->userName;
        }

        throw new ErrorException('Unknown sender_type' . $senderType);
    }
}
