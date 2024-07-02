<?php

namespace App\Services\PostfixLog\Actions;

use App\Services\PostfixLog\Dtos\OpensearchConvertedResults;
use App\Services\PostfixLog\Dtos\PostfixLogRow;
use App\Services\PostfixLog\Dtos\PostfixMail;

class ConvertOpensearchResultToPostfixLogRows
{
    /**
     * @return PostfixLogRow[]
     */
    public function execute(array $result): OpensearchConvertedResults
    {
        $objs = [];
        foreach($result['hits']['hits'] as $hit) {
            $objs[] = new PostfixMail($hit['_source']);
        }

        return new OpensearchConvertedResults(
            $objs, $result['hits']['total']['value'], $result['hits']['total']['relation']
        );
    }
}
