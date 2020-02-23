<?php

declare(strict_types=1);

namespace Sapphire\App\Http\Action\Product;

use Psr\Http\Message\ServerRequestInterface;
use Sapphire\App\Core\JsonResponse;

class GetItemDetails
{
    public function __invoke(ServerRequestInterface $request)
    {
        $order = [
            'productId' => $request->getParsedBody()['productId'],
            'quantity' => $request->getParsedBody()['quantity']
        ];

        return JsonResponse::ok([
            'message' => 'POST request to /orders', 'order' => $order,
        ]);
    }
}
