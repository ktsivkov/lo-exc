<?php
declare(strict_types=1);

namespace App\Factory;

use App\Response\CountResponse;

readonly class CountResponseFactory implements CountResponseFactoryInterface
{
    public function get(int $counter): CountResponse
    {
        return new CountResponse(counter: $counter);
    }
}
