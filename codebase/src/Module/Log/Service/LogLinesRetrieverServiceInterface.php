<?php
declare(strict_types=1);

namespace App\Module\Log\Service;

use App\Module\Log\Dto\SearchCriteriaDto;

interface LogLinesRetrieverServiceInterface
{
    public function getLinesCount(SearchCriteriaDto $criteria): int;
}
