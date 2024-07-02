<?php

namespace App\Http\Controllers\API\Logging;

use App\Http\Controllers\Controller;
use App\Services\PostfixLog\Actions\GetPostfixMailFromOpensearch;
use App\Services\PostfixLog\Dtos\PostfixMail;
use App\Services\PostfixLog\Resources\PostfixMailResource;
use Illuminate\Http\Request;

class PostfixLogDetailController extends Controller
{
    public function __invoke(
        Request $request,
        string $queueId,
    ) {
        $result = app(GetPostfixMailFromOpensearch::class)->execute($queueId);

        return new PostfixMailResource(
            new PostfixMail($result['hits']['hits'][0]['_source'])
        );
    }
}
