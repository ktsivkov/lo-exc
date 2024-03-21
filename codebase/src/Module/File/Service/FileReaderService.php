<?php
declare(strict_types=1);

namespace App\Module\File\Service;

use App\Module\File\Dto\LinesResultDto;
use App\Module\File\Factory\LinesResultFactoryInterface;
use App\Module\File\Service\Exception\FileCannotReadException;
use App\Module\File\Service\Exception\FileDoesNotExistException;
use App\Module\File\Service\Exception\FileIsDirException;

readonly class FileReaderService implements FileReaderServiceInterface
{
    public function __construct(
        private LinesResultFactoryInterface $resultFactory,
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function readLines(string $fileLocation, int $batchSize, int $offset): LinesResultDto
    {
        if (!file_exists($fileLocation)) {
            throw new FileDoesNotExistException($fileLocation);
        }

        if (!is_file($fileLocation)) {
            throw new FileIsDirException($fileLocation);
        }

        return $this->readWithOffset(fileLocation: $fileLocation, batchSize: $batchSize, offset: $offset);
    }

    /**
     * @param string $fileLocation
     * @param int $batchSize
     * @param int $offset
     * @return LinesResultDto
     * @throws FileCannotReadException
     */
    private function readWithOffset(string $fileLocation, int $batchSize, int $offset): LinesResultDto
    {
        $file = fopen($fileLocation, "r");
        if (!$file) {
            throw new FileCannotReadException($fileLocation);
        }
        $lines = [];
        fseek($file, $offset);
        while (!feof($file) && count($lines) < $batchSize) {
            $line = fgets($file);
            if ($line) { // If pointer is at the end of the file this will be false
                // Remove the zero width spaces from the line
                $lines[] = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $line);
            }
        }
        $readBytesOffset = ftell($file);
        fclose($file);

        return $this->resultFactory->createResult(fileLocation: $fileLocation, offset: $readBytesOffset, lines: $lines);
    }
}
