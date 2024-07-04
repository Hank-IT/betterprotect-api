<?php

namespace Tests;

use App\Services\Authentication\Models\User;
use App\Services\PostfixLog\Actions\GetAndConvertAggregatedPostfixMailsFromOpensearch;
use App\Services\PostfixLog\RefactorMeParser;
use App\Services\Recipients\Models\RelayRecipient;
use App\Services\Server\Actions\GetPostfixSyslogFromServer;
use App\Services\Server\Models\Server;
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

        //$this->getJson(url('/api/v1/servers/2/postfix-queue/4WCrTY2Qn3z10Fq'))->dump();

        $result = app(GetPostfixSyslogFromServer::class)->execute(
            Server::find(2),
            Carbon::parse('01.07.2024'),
            Carbon::parse('04.07.2024'),
            1,
            15
        );

        dump($result);

       // dump(count($result));

        //$parser = new RefactorMeParser;

       // dump($parser->parse($result));
       // dump(count($parser->parse($result)));
    }
}
