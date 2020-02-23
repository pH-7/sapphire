<?php

declare(strict_types=1);

namespace Sapphire\App\Core;

use FastRoute\Dispatcher;
use FastRoute\Dispatcher\RegexBasedAbstract;
use FastRoute\RouteCollector;
use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Teapot\StatusCode;

final class Router
{
    private const INTERNAL_ERROR_MESSAGE = 'Something went wrong with routing';

    private RegexBasedAbstract $dispatcher;

    public function __construct(RouteCollector $routes)
    {
        $this->dispatcher = new Dispatcher\GroupCountBased($routes->getData());
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(), $request->getUri()->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return new Response(
                    StatusCode::NOT_FOUND,
                    ['Content-Type' => 'text/plain'],
                    'Not found'
                );

            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(
                    StatusCode::METHOD_NOT_ALLOWED,
                    [
                        'Content-Type' => 'text/plain',
                        'Method not allowed'
                    ]
                );

            case Dispatcher::FOUND:
                return $routeInfo[1]($request, ...array_values($routeInfo[2]));

        }

        throw new LogicException(self::INTERNAL_ERROR_MESSAGE);
    }
}
