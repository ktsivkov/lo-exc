<?php
declare(strict_types=1);

namespace App\Response;

readonly class CountErrorResponse
{
    public function __construct(public string $message, public string $parameter, public mixed $parameterValue)
    {
    }
}
