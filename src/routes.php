<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use Sapphire\App\Http\Action\Product\GetItemDetails;
use Sapphire\App\Http\Action\Product\GetItems;
use Sapphire\App\Http\Action\Utility\StaticFiles;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(static function (RouteCollector $routes) use ($products, $orders) {
    $routes->get('/products', GetItems::class);
    $routes->get('/product/{id:\d+}', GetItemDetails::class);
    $routes->get('/static/{file:.*\.\w+}', StaticFiles::class);
});
