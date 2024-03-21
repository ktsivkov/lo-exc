<?php
declare(strict_types=1);

namespace App\Response;

readonly class CountResponse
{
    public function __construct(public int $counter)
    {
    }
}
