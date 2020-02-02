<?php

declare(strict_types=1);

namespace Sapphire\App\Core;

use Psr\Http\Message\ServerRequestInterface;
use Teapot\StatusCode;
use Throwable;

final class ErrorHandler
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (Throwable $error) {
            return new Response(
                StatusCode::INTERNAL_SERVER_ERROR,
                ['Content-type' => 'application/json'],
                json_encode(['message' => $error->getMessage()], JSON_THROW_ON_ERROR, 512)
            );
        }
    }
}
