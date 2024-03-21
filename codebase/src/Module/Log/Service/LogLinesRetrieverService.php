<?php
declare(strict_types=1);

namespace App\Module\Log\Service;

use App\Module\Log\Dto\SearchCriteriaDto;
use App\Module\Log\Repository\LogLineRepositoryInterface;

readonly class LogLinesRetrieverService implements LogLinesRetrieverServiceInterface
{
    public function __construct(private LogLineRepositoryInterface $lineRepository)
    {
    }

    public function getLinesCount(SearchCriteriaDto $criteria): int
    {
        return $this->lineRepository->getLinesCountByCriteria($criteria);
    }
}
