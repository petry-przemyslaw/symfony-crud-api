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
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "employee", description: "Zarządzanie pracownikami")]
class EmployeeController extends ApiAbstractController
{
    #[Route('/company/{companyId}/employee', methods: ['GET'])]
    #[OA\Get(
        summary: "Pobierz listę pracowników firmy",
        tags: ["employee"],
        parameters: [
            new OA\Parameter(
                name: "companyId",
                in: "path",
                description: "ID firmy",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Lista pracowników firmy")
        ]
    )]
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

    #[Route('/company/{companyId}/employee', methods: ['POST'])]
    #[OA\Post(
        summary: "Utwórz nowego pracownika w firmie",
        tags: ["employee"],
        responses: [
            new OA\Response(response: 201, description: "Pracownik został utworzony"),
            new OA\Response(response: 400, description: "Błędne dane wejściowe")
        ]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['firstName', 'lastName', 'email'],
            properties: [
                new OA\Property(property: 'first_name', type: 'string', example: 'Kasia'),
                new OA\Property(property: 'last_name', type: 'string', example: 'Kowalska'),
                new OA\Property(property: 'email', type: 'string', example: 'kasia.kowalska@example.com'),
                new OA\Property(property: 'phone_number', type: 'string', example: '123456789', nullable: true),
            ]
        )
    )]
    public function create(CreateEmployeeHandler $handler, CreateEmployeeRequest $request): JsonResponse
    {
        try {
            return new JsonResponse($handler->handle($request), Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->handleException($exception, $request);
        }
    }

    #[Route('/company/{companyId}/employee/{employeeId}', methods: ['GET'])]
    #[OA\Get(
        summary: "Pobierz dane pracownika po ID i firmie",
        tags: ["employee"],
        parameters: [
            new OA\Parameter(
                name: "companyId",
                in: "path",
                description: "ID firmy",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "employeeId",
                in: "path",
                description: "ID pracownika",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Dane pracownika"),
            new OA\Response(response: 404, description: "Pracownik lub firma nie znaleziona")
        ]
    )]
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

    #[Route('/company/{companyId}/employee/{employeeId}', methods: ['PUT'])]
    #[OA\Put(
        summary: "Aktualizuj dane pracownika",
        tags: ["employee"],
        responses: [
            new OA\Response(response: 204, description: "Dane pracownika zostały zaktualizowane"),
            new OA\Response(response: 400, description: "Błędne dane wejściowe"),
            new OA\Response(response: 404, description: "Pracownik lub firma nie znaleziona")
        ]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['firstName', 'lastName', 'email'],
            properties: [
                new OA\Property(property: 'first_name', type: 'string', example: 'Kasia'),
                new OA\Property(property: 'last_name', type: 'string', example: 'Kowalska'),
                new OA\Property(property: 'email', type: 'string', example: 'kasia.kowalska@example.com'),
                new OA\Property(property: 'phone_number', type: 'string', example: '123456789', nullable: true),
            ]
        )
    )]
    public function update(MessageBusInterface $messageBus, UpdateEmployeeCommand $command): JsonResponse
    {
        try {
            $messageBus->dispatch($command);
            return new JsonResponse($command, Response::HTTP_NO_CONTENT);
        } catch (HandlerFailedException $exception) {
            return $this->handleException($exception->getPrevious() ?? $exception, $command);
        }
    }

    #[Route('/company/{companyId}/employee/{employeeId}', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Usuń pracownika z firmy",
        description: "Usuwa pracownika o podanym ID z podanej firmy.",
        tags: ["employee"],
        parameters: [
            new OA\Parameter(
                name: "companyId",
                in: "path",
                description: "ID firmy",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "employeeId",
                in: "path",
                description: "ID pracownika",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 204, description: "Pracownik został usunięty pomyślnie"),
            new OA\Response(response: 404, description: "Pracownik lub firma nie znaleziona")
        ]
    )]
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
