<?php

namespace App\Services\PostfixLog\Actions;

use App\Services\PostfixLog\Dtos\LogSearchDto;
use App\Services\PostfixLog\Dtos\OpensearchConvertedResults;
use App\Services\PostfixLog\Dtos\PostfixLogRow;
use Carbon\Carbon;

class GetParsedPostfixLogsFromOpensearch
{
    public function __construct(
        protected GetPostfixLogsFromOpensearch $getPostfixLogsFromOpensearch,
        protected ConvertOpensearchResultToPostfixLogRows $convertOpensearchResultToPostfixLogRows,
    ) {}

    /**
     * @return PostfixLogRow[]
     */
    public function execute(
        ?Carbon $start,
        ?Carbon $end,
        int $from,
        int $size,
        ?LogSearchDto $logSearchDto,
    ): OpensearchConvertedResults {
        $result =  $this->getPostfixLogsFromOpensearch->execute(
            config('betterprotect.opensearch-postfix-parsed'), $start, $end, $from, $size, $logSearchDto,
        );

        return $this->convertOpensearchResultToPostfixLogRows->execute($result);
    }
}
