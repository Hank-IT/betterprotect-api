<?php

namespace App\Jobs;

use Carbon\Carbon;
use Email\Parse;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use App\Models\RelayRecipient;
use Illuminate\Support\Facades\Log;
use App\Services\LdapEmailSanitizer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class QueryLdapRecipients implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $addresses;

    protected $task;

    protected $ldapConnection;

    protected $ignoredDomains;

    public function __construct($addresses, Task $task, $ldapConnection, ?string $ignoredDomains)
    {
        $this->addresses = $addresses;

        $this->task = $task;

        $this->ldapConnection = $ldapConnection;

        $this->ignoredDomains = $ignoredDomains;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $addresses = unserialize(base64_decode($this->addresses));

        $addresses = app(LdapEmailSanitizer::class, ['addresses' => $addresses, 'ignoredDomains' => $this->ignoredDomains])->sanitize();

        $source = 'ldap:' . $this->ldapConnection;

        $this->task->update(['message' => 'LDAP ' .  $this->ldapConnection . ': Abfrage erfolgreich. Empfänger werden eingefügt...']);

        // Parse and validate addresses
        $parsedLdapRecipients = $addresses->map(function($address) {
            $address = $this->parseAddress($address);

            // Skip if address is invalid
            if (! $address) {
                return null;
            }

            return $address['address'];
        })->values();

        // Let's use eloquent to insert this.
        // Disclaimer: I am fully aware that this will execute an insert query for every record.
        $parsedLdapRecipients->each(function($address) use($source) {
            RelayRecipient::firstOrcreate([
                'payload' => $address,
                'data_source' => $source,
                'action' => 'OK',
            ]);
        });

        $this->task->update(['message' => 'LDAP ' .  $this->ldapConnection . ': Alte Empfänger werden entfernt...']);

        // Diff data and remove non existent records
        // Pull all records
        $ldapRecipientFromDatabase = RelayRecipient::where('data_source', '=', $source)->get();

        // Find records which are inside the database, but not inside the ldap
        $ldapRecipientFromDatabase->each(function($address) use($parsedLdapRecipients) {
            if ($parsedLdapRecipients->search($address->payload) === false) {
                // Address is not inside the ldap anymore
                Log::debug('SyncLdapAddresses: Removing address ' . $address . ' from database.');

                $address->delete();
            }
        });

        $this->task->update([
            'message' => 'LDAP ' .  $this->ldapConnection . ': Empfänger wurden erfolgreich aktualisiert.',
            'status' => Task::STATUS_FINISHED,
            'endDate' => Carbon::now(),
        ]);
    }

    protected function parseAddress($address)
    {
        $parser = Parse::getInstance();

        $result = $parser->parse($address);

        if (! $result['success']) {
            Log::debug('SyncLdapAddresses: Parsing address ' . $address . ' failed.');

            return false;
        }

        $address = $result['email_addresses'];

        if (is_array($address)) {
            $address = $address[0];
        }

        return $address;
    }
}
