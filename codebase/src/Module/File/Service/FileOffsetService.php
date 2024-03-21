<?php
declare(strict_types=1);

namespace App\Module\File\Service;

use App\Module\File\Repository\FileReadOffsetRepositoryInterface;

readonly class FileOffsetService implements FileOffsetServiceInterface
{
    public function __construct(private FileReadOffsetRepositoryInterface $offsetRepository)
    {
    }

    public function getFileOffset(string $fileLocation): int
    {
        $offset = 0;
        $offsetEntity = $this->offsetRepository->findOneByFileLocation(fileLocation: $fileLocation);
        if ($offsetEntity) {
            $offset = $offsetEntity->getOffsetPointer();
        }
        return $offset;
    }

    public function saveFileOffset(string $fileLocation, int $offset): void
    {
        $this->offsetRepository->saveFileOffset(fileLocation: $fileLocation, offset: $offset);
    }
}
