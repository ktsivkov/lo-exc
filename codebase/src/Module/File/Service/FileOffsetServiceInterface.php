<?php
declare(strict_types=1);

namespace App\Module\File\Service;

interface FileOffsetServiceInterface
{
    public function getFileOffset(string $fileLocation): int;

    public function saveFileOffset(string $fileLocation, int $offset): void;
}
