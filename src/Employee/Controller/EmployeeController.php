<?php

declare(strict_types=1);

namespace App\Employee\Controller;

use App\Company\Controller\ApiAbstractController;
use App\Company\Domain\ValueObject\CompanyId;
use App\Employee\Application\Command\DeleteEmployeeCommand;
use App\Employee\Application\Command\UpdateEmployeeCommand;
use App\Employee\Application\DTO\CreateEmployeeRequest;
use App\Employee\Application\DTO\EmployeeCompanyRequestQuery;
use App\Employee\Application\Query\GetAllEmployeeQuery;
use App\Employee\Application\Query\GetEmployeeQuery;
use App\Employee\Application\Service\CreateEmployeeHandler;
use App\Employee\Domain\ValueObject\EmployeeId;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class EmployeeController extends ApiAbstractController
{
    public function collect(GetAllEmployeeQuery $query, int $companyId): JsonResponse
    {
        $companyIdValue = null;
        try {
            $companyIdValue = new CompanyId($companyId);
            return new JsonResponse($query->query($companyIdValue)->getAll());
        } catch (Exception $exception) {
            return $this->handleException($exception, $companyIdValue);
        }
    }

    public function create(CreateEmployeeHandler $handler, CreateEmployeeRequest $request): JsonResponse
    {
        try {
            return new JsonResponse($handler->handle($request), Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->handleException($exception, $request);
        }
    }

    public function get(GetEmployeeQuery $query, int $companyId, int $employeeId): JsonResponse
    {
        $request = null;
        try {
            $request = new EmployeeCompanyRequestQuery(new CompanyId($companyId), new EmployeeId($employeeId));
            return new JsonResponse($query->query($request));
        } catch (Exception $exception) {
            return $this->handleException($exception, $request);
        }
    }

    public function update(MessageBusInterface $messageBus, UpdateEmployeeCommand $command): JsonResponse
    {
        try {
            $messageBus->dispatch($command);
            return new JsonResponse($command, Response::HTTP_NO_CONTENT);
        } catch (HandlerFailedException $exception) {
            return $this->handleException($exception->getPrevious() ?? $exception, $command);
        }
    }

    public function delete(MessageBusInterface $messageBus, int $companyId, int $employeeId): JsonResponse
    {
        $command = new DeleteEmployeeCommand($companyId, $employeeId);
        try {
            $messageBus->dispatch($command);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (HandlerFailedException $exception) {
            return $this->handleException($exception->getPrevious() ?? $exception, $command);
        }
    }
}