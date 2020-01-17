<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class ClientSenderAccessDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('client_sender_access')->insert([
            ['payload' => 'www.contoso.com', 'type' => 'client_hostname', 'action' => 'ok'],
            ['payload' => 'www.contoso.com', 'type' => 'client_hostname', 'action' => 'reject'],

            ['payload' => '10.10.10.10', 'type' => 'client_ipv4', 'action' => 'ok'],
            ['payload' => '10.10.10.10', 'type' => 'client_ipv4', 'action' => 'reject'],

            ['payload' => '192.168.100.0/24', 'type' => 'client_ipv4_net', 'action' => 'ok'],
            ['payload' => '192.168.100.0/24', 'type' => 'client_ipv4_net', 'action' => 'reject'],

            ['payload' => 'mail@contoso.com', 'type' => 'mail_from_address', 'action' => 'ok'],
            ['payload' => 'mail@contoso.com', 'type' => 'mail_from_address', 'action' => 'reject'],

            ['payload' => '@contoso.com', 'type' => 'mail_from_domain', 'action' => 'ok'],
            ['payload' => '@contoso.com', 'type' => 'mail_from_domain', 'action' => 'reject'],

            ['payload' => 'contoso.com', 'type' => 'mail_from_domain', 'action' => 'ok'],
            ['payload' => 'contoso.com', 'type' => 'mail_from_domain', 'action' => 'reject'],

            ['payload' => 'mail', 'type' => 'mail_from_localpart', 'action' => 'ok'],
            ['payload' => 'mail', 'type' => 'mail_from_localpart', 'action' => 'reject'],
        ]);
    }
}
