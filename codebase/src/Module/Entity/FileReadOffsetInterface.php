<?php
declare(strict_types=1);

namespace App\Module\Entity;

interface FileReadOffsetInterface
{
    public function getId(): ?int;

    public function getOffsetPointer(): ?int;

    public function setOffsetPointer(int $offsetPointer): static;

    public function getFilename(): ?string;

    public function setFilename(string $filename): static;
}
