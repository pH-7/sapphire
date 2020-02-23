<?php

declare(strict_types=1);

namespace Sapphire\App\Http\Action\Product;

use Psr\Http\Message\ServerRequestInterface;
use Sapphire\App\Core\JsonResponse;

class GetItems
{
    public function __invoke(ServerRequestInterface $request)
    {
        return JsonResponse::ok([
            'message' => 'POST request to /orders', 'order' => $order,
        ]);
    }
}
