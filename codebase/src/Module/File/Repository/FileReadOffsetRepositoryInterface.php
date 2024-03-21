<?php
declare(strict_types=1);

namespace App\Module\File\Repository;

use App\Module\Entity\FileReadOffsetInterface;

interface FileReadOffsetRepositoryInterface
{
    public function findOneByFileLocation(string $fileLocation): ?FileReadOffsetInterface;

    public function saveFileOffset(string $fileLocation, int $offset): void;
}
