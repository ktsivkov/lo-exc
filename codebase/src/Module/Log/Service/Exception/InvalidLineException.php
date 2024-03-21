<?php
declare(strict_types=1);

namespace App\Module\Log\Service\Exception;

use Exception;

class InvalidLineException extends Exception implements LinesProcessingException
{
    private const MSG = 'Invalid log line.';

    public function __construct(
        private readonly string $fileLocation,
        private readonly string $logLine,
    )
    {
        parent::__construct(self::MSG);
    }

    public function getFileLocation(): string
    {
        return $this->fileLocation;
    }

    public function getLogLine(): string
    {
        return $this->logLine;
    }
}