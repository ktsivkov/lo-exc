<?php
declare(strict_types=1);

namespace App\Module\Log\Factory;

use App\Module\Log\Dto\SearchCriteriaDto;
use App\Request\CountRequest;

interface SearchCriteriaFactoryInterface
{
    public function getCriteria(CountRequest $request): SearchCriteriaDto;
}
