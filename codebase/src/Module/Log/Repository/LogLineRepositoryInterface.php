<?php
declare(strict_types=1);

namespace App\Module\Log\Repository;

use App\Module\Entity\LogLineInterface;
use App\Module\Log\Dto\SearchCriteriaDto;

interface LogLineRepositoryInterface
{
    /**
     * @param array<LogLineInterface> $entities
     * @return void
     */
    public function saveLines(array $entities): void;

    public function getLinesCountByCriteria(SearchCriteriaDto $criteriaDto): int;
}
