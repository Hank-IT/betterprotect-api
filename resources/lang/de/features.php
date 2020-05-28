<?php

return [
    'amavis' => [
        'name' => 'Amavis Funktionen aktivieren',
    ],

    'console' => [
        'name' => 'SSH Funktionen aktivieren',
    ],

    'logging' => [
        'name' => 'Log Viewer aktivieren',
    ],

    'postfix' => [
        'name' => 'Postfix Funktionen aktivieren',
    ],

    'policy' => [
        'access' => [
            'any_client' => 'Beliebiger Client',
            'any_sender' => 'Beliebiger Absender',
            'client_types' => [
                'client_hostname' => 'Client Hostname',
                'client_reverse_hostname' => 'Client Reverse Hostname',
                'client_ipv4' => 'Client IPv4',
                'client_ipv6' => 'Client IPv6',
                'client_ipv4_net' => 'Client IPv4 Netzwerk',
            ],
            'sender_types' => [
                'mail_from_address' => 'Mail From Adresse',
                'mail_from_domain' => 'Mail From Domäne',
                'mail_from_localpart' => 'Mail From Localpart',
            ],
        ],
    ]
];
