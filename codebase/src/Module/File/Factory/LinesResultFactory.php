<?php
declare(strict_types=1);

namespace App\Module\File\Factory;

use App\Module\File\Dto\LinesResultDto;

readonly class LinesResultFactory implements LinesResultFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createResult(string $fileLocation, int $offset, array $lines): LinesResultDto
    {
        return new LinesResultDto(fileLocation: $fileLocation, offset: $offset, lines: $lines);
    }
}
