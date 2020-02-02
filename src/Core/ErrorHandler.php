<?php

declare(strict_types=1);

namespace Sapphire\App\Core;

use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final class ErrorHandler
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (Throwable $error) {
            return JsonResponse::internalServerError(
                $error->getMessage()
            );
        }
    }
}
