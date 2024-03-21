<?php
declare(strict_types=1);

namespace App\Request\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidQueryParameterException extends Exception implements InvalidParameter
{
    private const MSG = 'Invalid query parameter: %s';

    public function __construct(private readonly string $parameter, private readonly mixed $value, string $message)
    {
        parent::__construct(sprintf(self::MSG, $message), Response::HTTP_BAD_REQUEST);
    }

    public function getParameter(): string
    {
        return $this->parameter;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
