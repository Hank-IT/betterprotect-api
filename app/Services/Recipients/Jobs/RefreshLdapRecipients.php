<?php

namespace App\Services\Recipients\Jobs;

use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskFinished;
use App\Services\Tasks\Events\TaskProgress;
use Exception;
use App\Services\Recipients\Actions\FirstOrCreateRelayRecipient;
use App\Services\Recipients\Actions\PullRecipientsFromLdap;
use App\Services\Recipients\Models\RelayRecipient;
use App\Services\Tasks\Events\TaskCreated;
use App\Services\Tasks\Events\TaskStarted;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshLdapRecipients implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $uniqueTaskId,
        protected string $dataSource,
        string $dispatchingUserName,
        protected array $ignoredDomains = [],
    ) {
        TaskCreated::dispatch(
            $this->uniqueTaskId,
            'refresh-ldap-recipients',
            $dispatchingUserName,
        );
    }

    public function handle(
        PullRecipientsFromLdap $pullRecipientsFromLdap,
        FirstOrCreateRelayRecipient $firstOrCreateRelayRecipient,
    ): void {
        TaskStarted::dispatch(
            $this->uniqueTaskId,
            Carbon::now(),
        );

        try {
            TaskProgress::dispatch(
                $this->uniqueTaskId,
                'Querying email addresses from ldap.'
            );

            $emailsFromLdap = $pullRecipientsFromLdap->execute($this->ignoredDomains);

            TaskProgress::dispatch(
                $this->uniqueTaskId,
                'Querying email addresses from ldap successful.'
            );
        } catch(Exception $exception) {
            TaskFailed::dispatch(
                $this->uniqueTaskId,
                'An error occurred while querying the ldap for email addresses. Message: ' . $exception->getMessage(),
                Carbon::now(),
            );

            throw $exception;
        }

        try {
            TaskProgress::dispatch(
                $this->uniqueTaskId,
                'Inserting addresses into the database.'
            );


            foreach($emailsFromLdap as $email) {
                $firstOrCreateRelayRecipient->execute($email, $this->dataSource);
            }

            TaskProgress::dispatch(
                $this->uniqueTaskId,
                'Inserting addresses into the database was successful.'
            );
        } catch(Exception $exception) {
            TaskFailed::dispatch(
                $this->uniqueTaskId,
                'An error occurred while inserting the addresses into the database. Message: ' . $exception->getMessage(),
                Carbon::now(),
            );

            throw $exception;
        }

        try {
            TaskProgress::dispatch(
                $this->uniqueTaskId,
                'Expunging obsolete records from the database.'
            );

            // ToDo: Extract this to own action for easier testing
            // Find records which are inside the database, but not inside the ldap
            RelayRecipient::query()
                ->where('data_source', '=', $this->dataSource)
                ->get()
                ->each(function($address) use($emailsFromLdap) {
                    if (! in_array($address->payload, $emailsFromLdap)) {
                        // Address is not inside in data source anymore
                        $address->delete();
                    }
                });

            TaskProgress::dispatch(
                $this->uniqueTaskId,
                'Successfully expunged obsolete records from the database.'
            );
        } catch(Exception $exception) {
            TaskFailed::dispatch(
                $this->uniqueTaskId,
                'Failed to expunge obsolete records from the database. Message: ' . $exception->getMessage(),
                Carbon::now(),
            );

            throw $exception;
        }

        TaskFinished::dispatch(
            $this->uniqueTaskId,
            'Successfully refreshed email addresses from ldap.',
            Carbon::now(),
        );
    }
}
