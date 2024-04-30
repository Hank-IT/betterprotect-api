<?php

namespace Tests\Feature;

use App\Services\Authentication\Models\User;
use App\Services\Recipients\Models\RelayRecipient;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    use DatabaseTransactions;

    public function recipientDataProvider()
    {
        return [
            [
                [
                    'payload' => 'max.mustermann@contoso.com',
                    'action' => 'OK',
                    'data_source' => 'local',
                ],
            ],
            [
                [
                    'payload' => 'mail@example.org',
                    'action' => 'OK',
                    'data_source' => 'local',
                ],
            ],
            [
                [
                    'payload' => 'info@example.com',
                    'action' => 'OK',
                    'data_source' => 'local',
                ],
            ]
        ];
    }

    /**
     * @dataProvider recipientDataProvider
     * @test
     */
    public function it_creates_recipients_and_deletes_it($data)
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('recipient.store'), $data)->assertSuccessful();

        $this->assertDatabaseHas('relay_recipients', $data);

        $this->deleteJson(route('recipient.destroy', $response->json('data.id')))->assertSuccessful();

        $this->assertDatabaseMissing('relay_recipients', $data);
    }

    /**
     * @test
     */
    public function it_lists_recipients()
    {
        Sanctum::actingAs(User::factory()->create());

        $recipients = RelayRecipient::factory()->count(10)->create();

        $content = $this->getJson(route('recipient.index', ['currentPage' => 1, 'perPage' => 100]))
            ->assertSuccessful()
            ->getContent();

        foreach($recipients as $recipient) {
            $this->assertTrue(Str::contains($content, $recipient->getKey()));
        }
    }
}
