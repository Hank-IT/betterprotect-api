<?php

return [
    'console' => [
        'title' => 'Konsole',
        'name' => 'SSH Funktionen aktivieren',
    ],

    'logging' => [
        'title' => 'Log Viewer',
        'name' => 'Log Viewer aktivieren',
    ],

    'postfix' => [
        'title' => 'Postfix',
        'name' => 'Postfix Funktionen aktivieren',
    ],

    'ldap' => [
        'ldap' => 'LDAP',
        'auth' => 'Folgende Einstellungen werden gebraucht, wenn das LDAP für die Anmeldung an dieser Anwendung genutzt werden soll:',
    ],

    'server' => [
        'title' => 'Server',
        'disabled' => 'Server ist deaktiviert',
        'wizard' => 'Server Wizard',
        'configuration-finished' => 'Die Konfiguration ist abgeschlossen.',
    ],

    'policy' => [
        'install' => 'Policy installieren',
        'installation' => 'Policy Installation',
        'last-installation' => 'Letzte Policy Installation',

        'transport' => [
            'transport-placeholder' => 'z.B. smtp'
        ],

        'recipient' => [
            'store' => 'Empfänger hinzufügen'
        ],

        'relay_domain' => [
            'relay_domain' => 'Relay Domäne',
        ],

        'milter' => [
            'title' => 'Milter Ausnahme hinzufügen',
            'add-milter' => 'Milter hinzufügen',
        ],

        'access' => [
            'title' => 'Blacklist/Whitelist Eintrag',
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
