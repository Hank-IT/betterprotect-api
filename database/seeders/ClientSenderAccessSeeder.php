<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSenderAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('client_sender_access')->insert([
           /*
           ['type' => '', 'type' => '', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'client_hostname', 'type' => '', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'client_ipv4', 'type' => '', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'client_ipv4_net', 'type' => '', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'mail_from_address', 'type' => '', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'mail_from_domain', 'type' => '', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'mail_from_localpart', 'type' => '', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           */

           ['type' => 'client_hostname', 'payload' => 'mx00.contoso.com', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'client_ipv4', 'payload' => '127.0.0.1', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'client_ipv4_net', 'payload' => '127.0.0.0/24', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'mail_from_address', 'payload' => 'mail@contoso.com', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'mail_from_domain', 'payload' => 'contoso.com', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
           ['type' => 'mail_from_localpart', 'payload' => 'mail', 'action' => 'OK', 'description' => 'demo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
       ]);
    }
}
