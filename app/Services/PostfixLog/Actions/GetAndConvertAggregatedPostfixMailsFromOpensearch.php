<?php

namespace App\Services\PostfixLog\Actions;

use App\Services\PostfixLog\Dtos\LogSearchDto;
use App\Services\PostfixLog\Dtos\OpensearchConvertedResults;
use App\Services\PostfixLog\Dtos\PostfixRawLogRow;
use Carbon\Carbon;

class GetAndConvertAggregatedPostfixMailsFromOpensearch
{
    public function __construct(
        protected GetAggregatedPostfixMailsFromOpensearch $getAggregatedPostfixMailsFromOpensearch,
        protected ConvertOpensearchResultToPostfixDtos    $convertOpensearchResultToPostfixLogRows,
    ) {}

    /**
     * @return PostfixRawLogRow[]
     */
    public function execute(
        ?Carbon $start,
        ?Carbon $end,
        int $from,
        int $size,
        ?LogSearchDto $logSearchDto,
    ): OpensearchConvertedResults {
        $result =  $this->getAggregatedPostfixMailsFromOpensearch->execute(
            config('betterprotect.opensearch-postfix-parsed'), $start, $end, $from, $size, $logSearchDto,
        );

        return $this->convertOpensearchResultToPostfixLogRows->execute($result);
    }
}
