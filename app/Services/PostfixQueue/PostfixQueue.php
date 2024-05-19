<?php

namespace App\Services\PostfixQueue;

use App\Services\PostfixQueue\Contracts\DataDriver as DataDriverContract;
use App\Services\PostfixQueue\Dtos\PostfixQueueEntry;
use App\Services\PostfixQueue\Dtos\PostfixQueueEntryRecipients;
use Illuminate\Support\Facades\App;

class PostfixQueue
{
    public function __construct(protected DataDriverContract $dataDriver) {}

    /**
     * @return PostfixQueueEntry[]
     */
    public function get(): array
    {
        $output = App::call([$this->dataDriver, 'get']);

        // each mail is its own json object
        $output = explode("\n", $output);

        // remove last newline
        array_pop($output);

        $mails = [];

        foreach($output as $mail) {
            $payload = json_decode($mail, true);

            $recipients = [];
            foreach($payload['recipients'] ?? [] as $recipient) {
                $recipients = new PostfixQueueEntryRecipients($recipient['address'], $recipient['delay_reason']);
            }

            $mails[] = new PostfixQueueEntry(
                $payload['queue_name'],
                $payload['queue_id'],
                $payload['arrival_time'],
                $payload['message_size'],
                $payload['forced_expire'],
                $payload['sender'],
                $recipients,
            );
        }

        return $mails;
    }
}
