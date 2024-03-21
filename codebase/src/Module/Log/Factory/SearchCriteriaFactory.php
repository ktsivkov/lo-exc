<?php
declare(strict_types=1);

namespace App\Module\Log\Factory;

use App\Module\Log\Dto\SearchCriteriaDto;
use App\Request\CountRequest;

readonly class SearchCriteriaFactory implements SearchCriteriaFactoryInterface
{
    public function getCriteria(?CountRequest $request): SearchCriteriaDto
    {
        return new SearchCriteriaDto(
            serviceNames: $request?->serviceNames,
            statusCode: $request?->statusCode,
            startDate: $request?->startDate,
            endDate: $request?->endDate,
        );
    }
}
