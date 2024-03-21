<?php
declare(strict_types=1);

namespace App\Factory;

use App\Response\CountErrorResponse;
use App\Response\CountResponse;

interface CountResponseFactoryInterface
{
    public function get(int $counter): CountResponse;

    public function getError(string $message, string $parameter, mixed $parameterValue): CountErrorResponse;
}
