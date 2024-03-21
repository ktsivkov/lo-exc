<?php

namespace App\Module\Entity;

use DateTimeInterface;

interface LogLineInterface
{
    public function getId(): ?int;

    public function getServiceName(): ?string;

    public function setServiceName(string $serviceName): static;

    public function getLogFile(): ?string;

    public function setLogFile(string $logFile): static;

    public function getDate(): ?DateTimeInterface;

    public function setDate(DateTimeInterface $date): static;

    public function getIngestionDate(): ?DateTimeInterface;

    public function setIngestionDate(DateTimeInterface $ingestionDate): static;

    public function getStatusCode(): ?int;

    public function setStatusCode(int $statusCode): static;

    public function getLog(): ?string;

    public function setLog(string $log): static;
}