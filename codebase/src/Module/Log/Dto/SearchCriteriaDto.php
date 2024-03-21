<?php
declare(strict_types=1);

namespace App\Module\Log\Dto;

use DateTimeInterface;

readonly class SearchCriteriaDto
{
    public function __construct(
        public ?array             $serviceNames,
        public ?int               $statusCode,
        public ?DateTimeInterface $startDate,
        public ?DateTimeInterface $endDate,
    )
    {
    }
}