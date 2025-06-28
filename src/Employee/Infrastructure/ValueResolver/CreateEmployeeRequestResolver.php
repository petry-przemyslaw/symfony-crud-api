<?php

declare(strict_types=1);

namespace App\Employee\Infrastructure\ValueResolver;

use App\Employee\Application\DTO\CreateEmployeeRequest;
use App\Employee\Application\Factory\CreateEmployeeRequestFactory;
use InvalidArgumentException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class CreateEmployeeRequestResolver implements ValueResolverInterface
{
    public function __construct(private CreateEmployeeRequestFactory $factory)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== CreateEmployeeRequest::class) {
            return [];
        }

        try {
            yield $this->factory->create(
                (int)$request->get('companyId'),
                json_decode(
                    $request->getContent(),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                )
            );
        } catch (JsonException) {
            throw new InvalidArgumentException('invalid request body');
        }
    }
}