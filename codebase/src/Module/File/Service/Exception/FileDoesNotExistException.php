<?php
declare(strict_types=1);

namespace App\Module\File\Service\Exception;

use Exception;

class FileDoesNotExistException extends Exception implements FileValidationException
{
    private const MSG = 'File does not exist.';

    public function __construct(private readonly string $fileLocation)
    {
        parent::__construct(self::MSG);
    }

    public function getFileLocation(): string
    {
        return $this->fileLocation;
    }
}
