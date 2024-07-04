<?php

namespace App\Services\PostfixLog\Enums;

enum SearchableFieldsEnum: string
{
    case POSTFIX_QUEUEID = 'postfix_queueid';

    case POSTFIX_CLIENT_HOSTNAME = 'postfix_client_hostname';
    case POSTFIX_CLIENT_IP = 'postfix_client_ip';

    case POSTFIX_RELAY_HOSTNAME = 'postfix_relay_hostname';
    case POSTFIX_RELAY_IP = 'postfix_relay_ip';

    case POSTFIX_FROM = 'postfix_from';
    case POSTFIX_TO = 'postfix_to';

    case POSTFIX_HEADERS = 'postfix_headers';

    public function getFields(): array
    {
        return match ($this) {
            SearchableFieldsEnum::POSTFIX_QUEUEID => ['postfix_queueid'],
            SearchableFieldsEnum::POSTFIX_CLIENT_HOSTNAME => ['postfix_client_hostname', 'rcpt.postfix_client_hostname'],
            SearchableFieldsEnum::POSTFIX_CLIENT_IP => ['postfix_client_ip', 'rcpt.postfix_client_ip'],
            SearchableFieldsEnum::POSTFIX_RELAY_HOSTNAME => ['postfix_relay_hostname', 'rcpt.postfix_relay_hostname'],
            SearchableFieldsEnum::POSTFIX_RELAY_IP => ['postfix_relay_ip', 'rcpt.postfix_relay_ip'],
            SearchableFieldsEnum::POSTFIX_FROM => ['postfix_from'],
            SearchableFieldsEnum::POSTFIX_TO => ['postfix_to', 'rcpt.postfix_to'],
            SearchableFieldsEnum::POSTFIX_HEADERS => ['postfix_headers.value'],
        };
    }
}