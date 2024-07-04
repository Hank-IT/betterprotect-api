<?php

namespace Tests\Feature\Services\Recipients\Jobs;

use App\Services\Tasks\Events\TaskFailed;
use Exception;
use App\Services\Recipients\Actions\FirstOrCreateRelayRecipient;
use App\Services\Recipients\Actions\PruneObsoleteRecipientsForDataSource;
use App\Services\Recipients\Actions\PullRecipientsFromLdap;
use App\Services\Recipients\Models\RelayRecipient;
use App\Services\Tasks\Events\TaskFinished;
use App\Services\Tasks\Events\TaskProgress;
use Mockery;
use App\Services\Recipients\Jobs\RefreshLdapRecipients;
use App\Services\Tasks\Events\TaskCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Tests\TestCase;

class RefreshLdapRecipientsTest extends TestCase
{
    public function test_successful()
    {
        Event::fake();

        $job = new RefreshLdapRecipients(
            $id = (string)Str::uuid(),
            $dataSource = 'testing',
            'testing',
            $ignoredDomains = [(string)Str::uuid()]
        );

        $emailsFromLdap = [
            fake()->unique()->email,
            fake()->unique()->email,
            fake()->unique()->email,
        ];

        $pullRecipientsFromLdap = Mockery::mock(PullRecipientsFromLdap::class, function (MockInterface $mock) use ($ignoredDomains, $emailsFromLdap) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $ignoredDomains,
            ])->andReturn($emailsFromLdap);
        });

        $firstOrCreateRelayRecipient = Mockery::mock(FirstOrCreateRelayRecipient::class, function (MockInterface $mock) use ($emailsFromLdap, $dataSource) {
            foreach ($emailsFromLdap as $email) {
                $mock->shouldReceive('execute')->once()->withArgs([
                    $email,
                    $dataSource
                ])->andReturn(RelayRecipient::factory()->create([
                    'payload' => $email,
                    'data_source' => $dataSource,
                ]));
            }
        });

        $pruneObsoleteRecipientsForDataSource = Mockery::mock(PruneObsoleteRecipientsForDataSource::class, function (MockInterface $mock) use ($emailsFromLdap, $dataSource) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $emailsFromLdap,
                $dataSource,
            ]);
        });


        $job->handle(
            $pullRecipientsFromLdap,
            $firstOrCreateRelayRecipient,
            $pruneObsoleteRecipientsForDataSource,
        );

        Event::assertDispatched(TaskCreated::class);
        Event::assertDispatched(TaskProgress::class, 6);
        Event::assertDispatched(TaskFinished::class);
    }

    public function test_pull_recipients_failed()
    {
        $this->expectException(Exception::class);

        Event::fake();

        $job = new RefreshLdapRecipients(
            $id = (string)Str::uuid(),
            $dataSource = 'testing',
            'testing',
            $ignoredDomains = [(string)Str::uuid()]
        );

        $emailsFromLdap = [
            fake()->unique()->email,
            fake()->unique()->email,
            fake()->unique()->email,
        ];

        $pullRecipientsFromLdap = Mockery::mock(PullRecipientsFromLdap::class, function (MockInterface $mock) use ($ignoredDomains, $emailsFromLdap) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $ignoredDomains,
            ])->andThrow(new Exception);
        });

        $firstOrCreateRelayRecipient = Mockery::mock(FirstOrCreateRelayRecipient::class, function (MockInterface $mock) use ($emailsFromLdap, $dataSource) {
            foreach ($emailsFromLdap as $email) {
                $mock->shouldReceive('execute')->withArgs([$email, $dataSource])->never();
            }
        });

        $pruneObsoleteRecipientsForDataSource = Mockery::mock(PruneObsoleteRecipientsForDataSource::class, function (MockInterface $mock) use ($emailsFromLdap, $dataSource) {
            $mock->shouldReceive('execute')->withArgs([$emailsFromLdap, $dataSource])->never();
        });


        $job->handle(
            $pullRecipientsFromLdap,
            $firstOrCreateRelayRecipient,
            $pruneObsoleteRecipientsForDataSource,
        );

        Event::assertDispatched(TaskCreated::class);
        Event::assertDispatched(TaskProgress::class, 1);
        Event::assertDispatched(TaskFailed::class);
    }

    public function test_failed_to_create_recipients()
    {
        $this->expectException(Exception::class);

        Event::fake();

        $job = new RefreshLdapRecipients(
            $id = (string)Str::uuid(),
            $dataSource = 'testing',
            'testing',
            $ignoredDomains = [(string)Str::uuid()]
        );

        $emailsFromLdap = [
            fake()->unique()->email,
            fake()->unique()->email,
            fake()->unique()->email,
        ];

        $pullRecipientsFromLdap = Mockery::mock(PullRecipientsFromLdap::class, function (MockInterface $mock) use ($ignoredDomains, $emailsFromLdap) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $ignoredDomains,
            ])->andReturn($emailsFromLdap);
        });

        $firstOrCreateRelayRecipient = Mockery::mock(FirstOrCreateRelayRecipient::class, function (MockInterface $mock) use ($emailsFromLdap, $dataSource) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $emailsFromLdap[0],
                $dataSource
            ])->andThrow(new Exception);
        });

        $pruneObsoleteRecipientsForDataSource = Mockery::mock(PruneObsoleteRecipientsForDataSource::class, function (MockInterface $mock) use ($emailsFromLdap, $dataSource) {
            $mock->shouldReceive('execute')->withArgs([
                $emailsFromLdap,
                $dataSource,
            ])->never();
        });

        $job->handle(
            $pullRecipientsFromLdap,
            $firstOrCreateRelayRecipient,
            $pruneObsoleteRecipientsForDataSource,
        );

        Event::assertDispatched(TaskCreated::class);
        Event::assertDispatched(TaskProgress::class, 3);
        Event::assertDispatched(TaskFailed::class);
    }

    public function test_failed_to_prune_obsolete_records()
    {
        $this->expectException(Exception::class);

        Event::fake();

        $job = new RefreshLdapRecipients(
            $id = (string)Str::uuid(),
            $dataSource = 'testing',
            'testing',
            $ignoredDomains = [(string)Str::uuid()]
        );

        $emailsFromLdap = [
            fake()->unique()->email,
            fake()->unique()->email,
            fake()->unique()->email,
        ];

        $pullRecipientsFromLdap = Mockery::mock(PullRecipientsFromLdap::class, function (MockInterface $mock) use ($ignoredDomains, $emailsFromLdap) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $ignoredDomains,
            ])->andReturn($emailsFromLdap);
        });

        $firstOrCreateRelayRecipient = Mockery::mock(FirstOrCreateRelayRecipient::class, function (MockInterface $mock) use ($emailsFromLdap, $dataSource) {
            foreach ($emailsFromLdap as $email) {
                $mock->shouldReceive('execute')->once()->withArgs([
                    $email,
                    $dataSource
                ])->andReturn(RelayRecipient::factory()->create([
                    'payload' => $email,
                    'data_source' => $dataSource,
                ]));
            }
        });

        $pruneObsoleteRecipientsForDataSource = Mockery::mock(PruneObsoleteRecipientsForDataSource::class, function (MockInterface $mock) use ($emailsFromLdap, $dataSource) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $emailsFromLdap,
                $dataSource,
            ])->andThrow(new Exception);
        });


        $job->handle(
            $pullRecipientsFromLdap,
            $firstOrCreateRelayRecipient,
            $pruneObsoleteRecipientsForDataSource,
        );

        Event::assertDispatched(TaskCreated::class);
        Event::assertDispatched(TaskProgress::class, 5);
        Event::assertDispatched(TaskFailed::class);
    }
}
