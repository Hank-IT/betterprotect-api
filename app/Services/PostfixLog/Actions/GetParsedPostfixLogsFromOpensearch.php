<?php

namespace App\Services\PostfixLog\Actions;

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
        ?string $search,
    ): array {
        $result =  $this->getPostfixLogsFromOpensearch->execute(
            config('betterprotect.opensearch-postfix-parsed'), $start, $end, $from, $size, $search,
        );

        return $this->convertOpensearchResultToPostfixLogRows->execute($result);
    }
}
