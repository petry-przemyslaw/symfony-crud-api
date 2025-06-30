<?php

declare(strict_types=1);

namespace App\Company\Application\Command;

use JsonSerializable;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateCompanyCommand',
    type: 'object',
    required: ['id', 'name', 'nip', 'address', 'city', 'postCode'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Example Company'),
        new OA\Property(property: 'nip', type: 'string', example: '1234567890'),
        new OA\Property(property: 'address', type: 'string', example: '123 Example St.'),
        new OA\Property(property: 'city', type: 'string', example: 'Example City'),
        new OA\Property(property: 'postCode', type: 'string', example: '00-000'),
    ]
)]
readonly class UpdateCompanyCommand implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $name,
        public string $nip,
        public string $address,
        public string $city,
        public string $postCode
    ) {
    }

    /**
     * @return array<string, string|int>
     */
    public function jsonSerialize(): array
    {
        return [
            'company_id' => $this->id,
            'name' => $this->name,
            'nip' => $this->nip,
            'address' => $this->address,
            'city' => $this->city,
            'post_code' => $this->postCode
        ];
    }
}