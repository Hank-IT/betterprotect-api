<?php

namespace Tests\Feature\Services\Milter\Actions;

use App\Services\Milter\Actions\SyncMilterExceptionsWithMilters;
use App\Services\Milter\Models\Milter;
use App\Services\Milter\Models\MilterException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SyncMilterExceptionsWithMiltersTest extends TestCase
{
    public function test()
    {


        $milterException = MilterException::factory()->create();

        // ids to be added
        $miltersToBeAdded = Milter::factory()->count(2)->create();

        // ids to be removed
        $miltersToBeRemoved = Milter::factory()->count(2)->create();
        $this->addMiltersToException($milterException, $miltersToBeRemoved->pluck('id')->toArray());

        // ids to be ignored
        $miltersToBeIgnored = Milter::factory()->count(2)->create();
        $this->addMiltersToException($milterException, $miltersToBeIgnored->pluck('id')->toArray());

        app(SyncMilterExceptionsWithMilters::class)->execute($milterException, $included = [
            ...$miltersToBeAdded->pluck('id')->toArray(),
            ...$miltersToBeIgnored->pluck('id')->toArray(),
        ]);

        foreach ($included as $item) {
            $this->assertDatabaseHas('milter_milter_exception', [
                'milter_id' => $item,
                'milter_exception_id' => $milterException->getKey()
            ]);
        }

        foreach ($miltersToBeRemoved as $item) {
            $this->assertDatabaseMissing('milter_milter_exception', [
                'milter_id' => $item,
                'milter_exception_id' => $milterException->getKey()
            ]);
        }
    }

    protected function addMiltersToException(MilterException $milterException, array $ids)
    {
        foreach ($ids as $id) {
            DB::table('milter_milter_exception')->insert([
                'milter_exception_id' => $milterException->getKey(),
                'milter_id' => $id,
            ]);
        }
    }
}
