<?php
declare(strict_types=1);

namespace App\Module\File\Service;

use App\Module\File\Dto\LinesResultDto;
use App\Module\File\Service\Exception\FileCannotReadException;
use App\Module\File\Service\Exception\FileDoesNotExistException;
use App\Module\File\Service\Exception\FileIsDirException;

interface FileReaderServiceInterface
{
    /**
     * @param string $fileLocation
     * @param int $batchSize
     * @param int $offset
     * @return LinesResultDto
     * @throws FileCannotReadException
     * @throws FileDoesNotExistException
     * @throws FileIsDirException
     */
    public function readLines(string $fileLocation, int $batchSize, int $offset): LinesResultDto;
}
