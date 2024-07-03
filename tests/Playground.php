<?php

namespace Tests;

use App\Services\Authentication\Models\User;
use App\Services\PostfixLog\Actions\GetAndConvertAggregatedPostfixMailsFromOpensearch;
use App\Services\PostfixLog\RefactorMeParser;
use App\Services\Recipients\Models\RelayRecipient;
use Carbon\Carbon;

class Playground extends TestCase
{
    public function test()
    {
        $this->be(User::first());

        //RelayRecipient::factory()->count(100)->create();

        //return;

        $pageSize = 15;
        $pageNumber = 1;

       /* $result = app(GetParsedPostfixLogsFromOpensearch::class)->execute(
            Carbon::parse('2024-06-22 00:00'), Carbon::parse('2024-06-22 23:59'), 0, 10000, null
        );

        dump($result);*/

        $this->getJson(url('/api/v1/postfix/log/44B2CFFA96'))->dump();

       // dump(count($result));

        //$parser = new RefactorMeParser;

       // dump($parser->parse($result));
       // dump(count($parser->parse($result)));
    }
}
