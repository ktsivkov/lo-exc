<?php
declare(strict_types=1);

namespace App\Module\File\Factory;

use App\Module\File\Dto\LinesResultDto;

interface LinesResultFactoryInterface
{
    /**
     * @param string $fileLocation
     * @param int $offset
     * @param array<string> $lines
     * @return LinesResultDto
     */
    public function createResult(string $fileLocation, int $offset, array $lines): LinesResultDto;
}