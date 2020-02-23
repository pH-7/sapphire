<?php

declare(strict_types=1);

namespace Sapphire\App\Core;

use React\Http\Response;
use Teapot\StatusCode;

class JsonResponse extends Response
{
    public function __construct(int $statusCode, $data = null)
    {
        $data = $data === null ? null : json_encode($data, JSON_THROW_ON_ERROR, 512);
        parent::__construct($statusCode, ['Content-type' => 'application/json'], $data);
    }

    public static function ok($data): self
    {
        return new self(StatusCode::OK, $data);
    }

    public static function internalServerError(string $reason): self
    {
        return new self(StatusCode::INTERNAL_SERVER_ERROR, ['message' => $reason]);
    }

    public static function badRequest(string ...$errors): self
    {
        return new self(StatusCode::BAD_REQUEST, ['errors' => $errors]);
    }

    public static function unauthorized(): self
    {
        return new self(StatusCode::UNAUTHORIZED);
    }

    public static function noContent(): self
    {
        return new self(StatusCode::NO_CONTENT);
    }
}
