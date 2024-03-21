<?php
declare(strict_types=1);

namespace App\Module\File\Dto;

readonly class LinesResultDto
{

    /**
     * @param string $fileLocation
     * @param int $offset
     * @param array<string> $lines
     */
    public function __construct(
        public string $fileLocation,
        public int    $offset,
        public array  $lines,
    )
    {
    }

}