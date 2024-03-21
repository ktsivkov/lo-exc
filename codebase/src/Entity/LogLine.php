<?php
declare(strict_types=1);

namespace App\Entity;

use App\Module\Entity\LogLineInterface;
use App\Repository\LogLineRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogLineRepository::class)]
class LogLine implements LogLineInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $serviceName = null;

    #[ORM\Column(length: 255)]
    private ?string $logFile = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?DateTimeInterface $ingestionDate = null;

    #[ORM\Column]
    private ?int $statusCode = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $log = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): static
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function getLogFile(): ?string
    {
        return $this->logFile;
    }

    public function setLogFile(string $logFile): static
    {
        $this->logFile = $logFile;

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIngestionDate(): ?DateTimeInterface
    {
        return $this->ingestionDate;
    }

    public function setIngestionDate(DateTimeInterface $ingestionDate): static
    {
        $this->ingestionDate = $ingestionDate;

        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getLog(): ?string
    {
        return $this->log;
    }

    public function setLog(string $log): static
    {
        $this->log = $log;

        return $this;
    }
}
