<?php

namespace App\Http\Controllers\Tag;

use App\Http\Requests\Tag\TagRequest;
use App\Services\TagService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CheckTagController extends Controller
{
    public function __invoke(TagRequest $tagRequest, TagService $tagService) : JsonResponse {
        $uid = $tagRequest->uid();
        $message = $tagService->checkUsage($uid);

        return new JsonResponse([
            $message
        ]);
    }
}
