<?php

declare(strict_types=1);

namespace App\Employee\Infrastructure\ValueResolver;

use App\Employee\Application\Command\UpdateEmployeeCommand;
use App\Employee\Application\Factory\UpdateEmployeeCommandFactory;
use InvalidArgumentException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class UpdateEmployeeCommandResolver implements ValueResolverInterface
{
    public function __construct(private UpdateEmployeeCommandFactory $factory)
    {
    }

    /**
     * @return iterable<UpdateEmployeeCommand>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== UpdateEmployeeCommand::class) {
            return [];
        }
        try {
            yield $this->factory->create(
                (int)$request->get('companyId'),
                (int)$request->get('employeeId'),
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