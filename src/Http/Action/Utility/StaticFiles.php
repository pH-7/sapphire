<?php

namespace Sapphire\App\Http\Action\Utility;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Filesystem\FilesystemInterface;
use React\Filesystem\Node\FileInterface;
use React\Http\Response;
use React\Promise\PromiseInterface;
use React\Filesystem\Stream\ReadableStream;
use Sapphire\App\Core\JsonResponse;

final class StaticFiles
{
    private FilesystemInterface $filesystem;
    private string $projectRoot;

    public function __construct(
        FilesystemInterface $filesystem, string $projectRoot
    )
    {
        $this->filesystem = $filesystem;
        $this->projectRoot = $projectRoot;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $path = $this->projectRoot . $request->getUri()->getPath();
        $file = $this->filesystem->file($path);
        return $file->exists()->then(
            function () use ($file) {
                return $this->responseWithFile($file);
            },
            function (Exception $exception) {
                return JsonResponse::notFound();
            }
        );
    }

    private function responseWithFile(FileInterface $file): PromiseInterface
    {
        return $file->open('r')->then(
            function (ReadableStream $stream) {
                return new Response(
                    200,
                    ['Content-Type' => 'image/png'], $stream
                );
            },
            function (Exception $exception) {
                return JsonResponse::internalServerError(
                    $exception->getMessage()

                );
            }
        );
    }
}
