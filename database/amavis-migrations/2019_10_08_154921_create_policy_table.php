<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolicyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy', function (Blueprint $table) {
            $table->increments('id');
            $table->string('policy_name', 32);
            $table->char('virus_lover', 1)->nullable();
            $table->char('spam_lover', 1)->nullable();
            $table->char('banned_files_lover', 1)->nullable();
            $table->char('bad_header_lover', 1)->nullable();
            $table->char('bypass_virus_checks', 1)->nullable();
            $table->char('bypass_spam_checks', 1)->nullable();
            $table->char('bypass_banned_checks', 1)->nullable();
            $table->char('bypass_header_checks', 1)->nullable();
            $table->char('spam_modifies_subj', 1)->nullable();
            $table->string('virus_quarantine_to', 64)->nullable();
            $table->string('spam_quarantine_to', 64)->nullable();
            $table->string('banned_quarantine_to', 64)->nullable();
            $table->string('bad_header_quarantine_to', 64)->nullable();
            $table->string('clean_quarantine_to', 64)->nullable();
            $table->string('other_quarantine_to', 64)->nullable();
            $table->float('spam_tag_level')->nullable();
            $table->float('spam_tag2_level')->nullable();
            $table->float('spam_kill_level')->nullable();
            $table->float('spam_dsn_cutoff_level')->nullable();
            $table->float('spam_quarantine_cutoff_level')->nullable();
            $table->string('addr_extension_virus', 64)->nullable();
            $table->string('addr_extension_spam', 64)->nullable();
            $table->string('addr_extension_banned', 64)->nullable();
            $table->string('addr_extension_bad_header', 64)->nullable();
            $table->char('warnvirusrecip', 1)->nullable();
            $table->char('warnbannedrecip', 1)->nullable();
            $table->char('warnbadhrecip', 1)->nullable();
            $table->string('newvirus_admin', 64)->nullable();
            $table->string('virus_admin', 64)->nullable();
            $table->string('banned_admin', 64)->nullable();
            $table->string('bad_header_admin', 64)->nullable();
            $table->string('spam_admin', 64)->nullable();
            $table->string('spam_subject_tag', 64)->nullable();
            $table->string('spam_subject_tag2', 64)->nullable();
            $table->integer('message_size_limit')->nullable();
            $table->string('banned_rulenames')->nullable();
        });
    }
}
