<?php

declare(strict_types=1);

namespace App\Employee\Application\Factory;

use App\Employee\Application\Command\UpdateEmployeeCommand;
use App\Shared\Application\Factory\AbstractRequestFactory;
use InvalidArgumentException;
use function sprintf;

class UpdateEmployeeCommandFactory extends AbstractRequestFactory
{
    private const FIRST_NAME = 'first_name';
    private const LAST_NAME = 'last_name';
    private const EMAIL = 'email';
    private const PHONE_NUMBER = 'phone_number';

    private const REQUIRED_TEXT_DATA = [
      self::FIRST_NAME,
      self::LAST_NAME,
      self::EMAIL
    ];

    /**
     * @param array{
     *     first_name: string,
     *     last_name: string,
     *     email: string,
     *     phone_number?: mixed
     * } $data
     *
     * @throws InvalidArgumentException
     */
    public function create(int $companyId, int $employeeId, array $data): UpdateEmployeeCommand
    {
        $this->validRequiredTextData(self::REQUIRED_TEXT_DATA, $data);
        if (isset($data[self::PHONE_NUMBER]) && !is_string($data[self::PHONE_NUMBER])) {
            throw new InvalidArgumentException(
                sprintf(
                    'field %s must be a string',
                    self::PHONE_NUMBER
                )
            );
        }

        return new UpdateEmployeeCommand(
            $companyId,
            $employeeId,
            $data[self::FIRST_NAME],
            $data[self::LAST_NAME],
            $data[self::EMAIL],
            $data[self::PHONE_NUMBER] ?? null
        );
    }
}