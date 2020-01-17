<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClientSenderAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_sender_access', function (Blueprint $table) {
            $table->string('client',1024);
            $table->string('sender',1024);
        });

        $clientSenderAccess = DB::table('client_sender_access')->get();

        $clientSenderAccess->each(function($row) {
            switch($row->type) {
                case 'mail_from_address':
                case 'mail_from_domain':
                case 'mail_from_localpart':
                    DB::table('client_sender_access')->where('id', '=', $row->id)->update(['sender' => $row->payload]);
                    break;
                case 'client_hostname':
                case 'client_ipv4':
                case 'client_ipv4_net':
                    DB::table('client_sender_access')->where('id', '=', $row->id)->update(['client' => $row->payload]);
                    break;
            }
        });

        Schema::table('client_sender_access', function (Blueprint $table) {
            $table->dropColumn('payload');
            $table->renameColumn('type', 'client_type');
        });

        Schema::table('client_sender_access', function (Blueprint $table) {
            $table->string('client_type')->nullable()->change();
        });

        // The type becomes client specific, so we remove the prefix here
        DB::table('client_sender_access')->where('client_type', '=', 'client_hostname')->update(['client_type' => 'hostname']);
        DB::table('client_sender_access')->where('client_type', '=', 'client_ipv4')->update(['client_type' => 'ipv4']);
        DB::table('client_sender_access')->where('client_type', '=', 'client_ipv4_net')->update(['client_type' => 'ipv4_net']);

        // mail types are not needed any longer
        DB::table('client_sender_access')->where('client_type', '=', 'mail_from_address')->update(['client_type' => null]);
        DB::table('client_sender_access')->where('client_type', '=', 'mail_from_domain')->update(['client_type' => null]);
        DB::table('client_sender_access')->where('client_type', '=', 'mail_from_localpart')->update(['client_type' => null]);
    }
}
