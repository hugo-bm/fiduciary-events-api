<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

class RequestLogger
{
    public static function log(string $level, string $message, array $context = []): void
    {
        Log::channel('system')->{$level}($message, array_merge(
            self::requestContext(),
            $context
        ));
    }

    private static function requestContext(): array
    {
        $request = request();

        if (!$request) {
            return [];
        }

        return [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_id' => optional($request->attributes->get('authenticated_user'))->id,
        ];
    }
}