<?php


namespace Sapphire\App\Http\Action\Product;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Respect\Validation\Validator;

final class Input
{
    private ServerRequestInterface $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function validate(): void
    {
        $nameValidator = Validator::key('name',
            Validator::allOf(
                Validator::notBlank(),
                Validator::stringType()
            ))->setName('name');

        $validator = Validator::allOf(
            $nameValidator
        );

        $validator->assert($this->request->getParsedBody());
    }

    public function name(): string
    {
        return $this->request->getParsedBody()['name'];
    }

    public function image(): ?UploadedFileInterface
    {
        $files = $this->request->getUploadedFiles(); return $files['image'] ?? null;
    }
}
