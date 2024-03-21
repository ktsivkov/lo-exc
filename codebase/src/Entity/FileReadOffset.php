<?php
declare(strict_types=1);

namespace App\Entity;

use App\Module\Entity\FileReadOffsetInterface;
use App\Repository\FileReadOffsetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileReadOffsetRepository::class)]
class FileReadOffset implements FileReadOffsetInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $offsetPointer = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffsetPointer(): ?int
    {
        return $this->offsetPointer;
    }

    public function setOffsetPointer(int $offsetPointer): static
    {
        $this->offsetPointer = $offsetPointer;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }
}
