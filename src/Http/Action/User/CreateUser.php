<?php

declare(strict_types=1);

namespace Sapphire\App\Http\Action\Product;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Sapphire\App\Core\JsonResponse;
use Sapphire\App\Exception\UserAlreadyExists;

class CreateUser
{
    public function __invoke(ServerRequestInterface $request)
    {
        $input = new Input($request);
        $input->validate();

        return $this->storage
            ->create($input->email(), $input->hashedPassword())->then(
                static function () {
                    return JsonResponse::created([]);
                })
            ->otherwise(
                static function (UserAlreadyExists $exception) {
                    return JsonResponse::badRequest('Email is already taken.'
                    );
                }
            )->otherwise(
                static function (Exception $exception) {
                    return JsonResponse::internalServerError(
                        $exception->getMessage());
                });
    }
}
