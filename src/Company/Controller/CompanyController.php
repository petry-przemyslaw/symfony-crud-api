<?php

declare(strict_types=1);

namespace App\Company\Controller;

use App\Company\Application\Command\DeleteCompanyCommand;
use App\Company\Application\Command\UpdateCompanyCommand;
use App\Company\Application\DTO\CompanyRequestQuery;
use App\Company\Application\Query\GetAllCompanyQuery;
use App\Company\Application\Query\GetCompanyQuery;
use App\Company\Domain\ValueObject\CompanyId;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Company\Application\DTO\CreateCompanyRequest;
use App\Company\Application\Service\CreateCompanyHandler;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Exception;

#[OA\Tag(name: "company", description: "Zarządzanie firmami")]
class CompanyController extends ApiAbstractController
{
    #[OA\Get(
        path: "/company/",
        summary: "Pobierz listę wszystkich firm",
        tags: ["company"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista firm"
            )
        ]
    )]
    public function collect(GetAllCompanyQuery $query): JsonResponse
    {
        try {
            return new JsonResponse($query->query()->getAll());
        } catch (Exception $exception) {
            return $this->handleException($exception, null);
        }
    }

    #[OA\Post(
        path: "/company/",
        summary: "Utwórz nową firmę",
        tags: ["company"],
        responses: [
            new OA\Response(response: 201, description: "Firma została utworzona"),
            new OA\Response(response: 400, description: "Błędne dane wejściowe")
        ]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['name', 'nip', 'address', 'city', 'postcode'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'mediamarkt'),
                new OA\Property(property: 'nip', type: 'string', example: '0104562801'),
                new OA\Property(property: 'address', type: 'string', example: 'ul. Przykładowa 7'),
                new OA\Property(property: 'city', type: 'string', example: 'Warszawa'),
                new OA\Property(property: 'postcode', type: 'string', example: '00-007'),
            ]
        )
    )]
    public function create(CreateCompanyHandler $companyHandler, CreateCompanyRequest $request): JsonResponse
    {
        try {
            return new JsonResponse($companyHandler->handle($request), Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->handleException($exception, $request);
        }
    }

    #[OA\Get(
        path: "/company/{companyId}",
        summary: "Pobierz szczegóły firmy po ID",
        tags: ["company"],
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
            new OA\Response(response: 200, description: "Szczegóły firmy"),
            new OA\Response(response: 404, description: "Firma nie znaleziona")
        ]
    )]
    public function get(GetCompanyQuery $query, int $companyId): JsonResponse
    {
        $request = null;

        try {
            $request = new CompanyRequestQuery(new CompanyId($companyId));
            return new JsonResponse($query->query($request));
        } catch (Exception $exception) {
            return $this->handleException($exception, $request);
        }
    }

    #[OA\Put(
        path: "/company/{companyId}",
        summary: "Aktualizuj dane firmy",
        tags: ["company"],
        responses: [
            new OA\Response(response: 204, description: "Dane firmy zostały zaktualizowane"),
            new OA\Response(response: 400, description: "Błędne dane wejściowe"),
            new OA\Response(response: 404, description: "Firma nie znaleziona")
        ]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['name', 'nip', 'address', 'city', 'postcode'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'mediamarkt'),
                new OA\Property(property: 'nip', type: 'string', example: '0104562801'),
                new OA\Property(property: 'address', type: 'string', example: 'ul. Przykładowa 7'),
                new OA\Property(property: 'city', type: 'string', example: 'Warszawa'),
                new OA\Property(property: 'postcode', type: 'string', example: '00-007'),
            ]
        )
    )]
    public function update(MessageBusInterface $messageBus, UpdateCompanyCommand $command): JsonResponse
    {
        try {
            $messageBus->dispatch($command);
            return new JsonResponse($command, Response::HTTP_NO_CONTENT);
        } catch (HandlerFailedException $exception) {
            return $this->handleException($exception->getPrevious() ?? $exception, $command);
        }
    }

    #[OA\Delete(
        path: "/company/{companyId}",
        summary: "Usuń firmę po ID",
        tags: ["company"],
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
            new OA\Response(response: 204, description: "Firma została usunięta"),
            new OA\Response(response: 404, description: "Firma nie znaleziona")
        ]
    )]
    public function delete(MessageBusInterface $messageBus, int $companyId): JsonResponse
    {
        $command = new DeleteCompanyCommand($companyId);
        try {
            $messageBus->dispatch($command);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (HandlerFailedException $exception) {
            return $this->handleException($exception->getPrevious() ?? $exception, $command);
        }
    }
}
