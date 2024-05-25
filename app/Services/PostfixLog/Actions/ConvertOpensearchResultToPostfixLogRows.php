<?php

namespace App\Services\PostfixLog\Actions;

use App\Services\PostfixLog\Dtos\PostfixLogRow;

class ConvertOpensearchResultToPostfixLogRows
{
    /**
     * @return PostfixLogRow[]
     */
    public function execute(array $result): array
    {
        $objs = [];
        foreach($result['hits']['hits'] as $hit) {
            $objs[] = new PostfixLogRow(
                $hit['_source']['message'], $hit['_source']['program'], $hit['_source']['timestamp8601'], $hit['_source']['logsource']
            );
        }

        return $objs;
    }
}
