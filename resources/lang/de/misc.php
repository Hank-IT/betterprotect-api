<?php

return [
    'app' => config('app.name'),

    'tasks' => [
        'tasks' => 'Aufgaben',
        'task' => 'Aufgabe',
    ],

    'auth' => [
        'login' => 'Login',
    ],

    'database' => [
        'host' => 'Datenbankhost',
        'name' => 'Datenbankname',
        'user' => 'Datenbankbenutzer',
        'password' => 'Datenbankpasswort',
        'port' => 'Datenbankport',
    ],

    'password-field-explanation' => [
        'security' => 'Das Passwort wird aus Sicherheitsgründen nicht angezeigt.',
        'new' => 'Geben Sie ein neues Passwort ein, um das Passwort zu ändern.',
        'empty' => 'Lassen Sie das Feld Leer, um das Passwort beizubehalten.',
        'clear' => 'Klicken Sie Leeren, um das Passwort zu entfernen.',
    ],

    'errors' => [
        'unknown' => 'Unbekannter Fehler'
    ],

    'clear' => 'Leeren',
    'start-date' => 'Startzeit',
    'end-date' => 'Endzeit',

    'password' => 'Passwort',
    'status' => 'Status',
    'choose-status' => 'Status wählen',
    'save_close' => 'Speichern & Schließen',
    'entry' => 'Eintrag',
    'choose_entry' => 'Bitte Eintrag auswählen',
    'entry-will-be-removed' => 'Der Eintrag wird gelöscht.',
    'are-you-sure' => 'Sind Sie sicher?',
    'yes' => 'Ja',
    'no' => 'Nein',
    'more-settings' => 'Weitere Einstellungen',
    'logout' => 'Logout',
    'refresh' => 'Aktualisieren',
    'empty' => 'Leer',
    'ipv4' => 'IPv4',
    'ipv6' => 'IPv6',
    'hostname' => 'Hostname',
    'loading' => 'Lade',
    'all' => 'Alles',
    'no-data-available' => 'Keine Daten vorhanden',
    'message' => 'Nachricht',
    'created_at' => 'Erstellt am',
    'options' => 'Optionen',
    'search' => 'Suche',
    'pagination' => 'Zeige Zeile :from bis :to von :total Zeilen.',
    'disabled' => 'Deaktiviert',

    'roles' => [
        'readonly' => 'Read Only',
        'authorizer' => 'Autorisierer',
        'editor' => 'Bearbeiter',
        'administrator' => 'Administrator',
    ],

    'menu' => [
        'main' => 'Hauptmenü',
        'server' => 'Server',
        'log_viewer' => 'Log Viewer',
        'charts' => 'Charts',
        'mail_flow' => 'Mail Flow',
        'queue' => 'Queue',
        'rules' => 'Regeln',
        'recipients' => 'Empfänger',
        'transport' => 'Transport',
        'relay_domains' => 'Relay Domänen',
        'milter' => 'Milter',
        'definitions' => 'Definitionen',
        'exceptions' => 'Ausnahmen',
        'user' => 'Benutzer',
        'ldap' => 'LDAP',
        'settings' => 'Einstellungen',
    ],
];
