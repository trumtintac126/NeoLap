<?php

namespace App\Concerns;

use Illuminate\Http\Response;

trait WithExceptionHandler
{
    public function handleException($request, $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
            return response()->json([
                'message' => $exception->getMessage() ?? 'Unauthorized Request',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}