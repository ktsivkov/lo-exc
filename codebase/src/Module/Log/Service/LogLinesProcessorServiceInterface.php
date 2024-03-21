<?php
declare(strict_types=1);

namespace App\Module\Log\Service;

use App\Module\Log\Service\Exception\InvalidLineException;

interface LogLinesProcessorServiceInterface
{
    /**
     * @param string $fileLocation
     * @param array<string> $lines
     * @return void
     * @throws InvalidLineException
     */
    public function processLines(string $fileLocation, array $lines): void;
}