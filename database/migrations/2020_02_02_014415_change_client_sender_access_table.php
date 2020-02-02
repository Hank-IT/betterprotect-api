<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeClientSenderAccessTable extends Migration
{
    public function up()
    {
        Schema::table('client_sender_access', function(Blueprint $table) {
            $table->string('client_type');
            $table->string('client_payload',1024)->nullable();

            $table->string('sender_type');
            $table->string('sender_payload',1024)->nullable();
        });

        $rows = DB::table('client_sender_access')->select(['id', 'payload', 'type'])->get();

        foreach ($rows as $row) {
            switch($row->type) {
                case 'client_hostname':
                    DB::table('client_sender_access')->where('id', '=', $row->id)->update(['client_type' => 'client_reverse_hostname', 'client_payload' => $row->payload, 'sender_type' => '*', 'sender_payload' => '*']);
                    break;
                case 'client_ipv4':
                case 'client_ipv4_net':
                    DB::table('client_sender_access')->where('id', '=', $row->id)->update(['client_type' => $row->type, 'client_payload' => $row->payload, 'sender_type' => '*', 'sender_payload' => '*']);
                    break;
                case 'mail_from_address':
                case 'mail_from_domain':
                case 'mail_from_localpart':
                    DB::table('client_sender_access')->where('id', '=', $row->id)->update(['sender_type' => $row->type, 'sender_payload' => $row->payload, 'client_type' => '*', 'client_payload' => '*']);
                    break;
                default:
                    throw new ErrorException('Unknown type');
            }
        }

        Schema::table('client_sender_access', function(Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('payload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
