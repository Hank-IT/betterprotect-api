<?php

namespace Tests;

use App\Services\PostfixLog\Actions\GetParsedPostfixLogsFromOpensearch;
use App\Services\PostfixLog\RefactorMeParser;
use Carbon\Carbon;

class Playground extends TestCase
{
    public function test()
    {
        $pageSize = 15;
        $pageNumber = 1;

        $result = app(GetParsedPostfixLogsFromOpensearch::class)->execute(
            Carbon::parse('2024-05-22 00:00'), Carbon::parse('2024-05-22 23:59'), 0, 100, null
        );

        $parser = new RefactorMeParser;

        dump($parser->parse($result));
    }
}
