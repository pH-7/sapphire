<?php

declare(strict_types=1);

namespace Sapphire\App\Core;

use Psr\Http\Message\ServerRequestInterface;

class JsonRequestDecoder
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $contentType = $request->getHeaderLine('Content-type');
        if ($contentType === 'application/json') {
            $body = $request->getBody()->getContents();
            $decodedBody = json_decode(
                $body,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            $request = $request->withParsedBody($decodedBody);
        }
        return $next($request);
    }

}
