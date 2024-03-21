<?php
declare(strict_types=1);

namespace App\Request;

use DateTimeInterface;

class CountRequest
{
    public const DATE_TIME_FORMAT = 'd/M/Y:H:i:s';

    /**
     * @param array|null $serviceNames
     * @param DateTimeInterface|null $startDate
     * @param DateTimeInterface|null $endDate
     * @param int|null $statusCode
     */
    public function __construct(
        public ?array             $serviceNames = null,
        public ?DateTimeInterface $startDate = null,
        public ?DateTimeInterface $endDate = null,
        public ?int               $statusCode = null,
    )
    {
    }
}
