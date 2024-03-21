<?php
declare(strict_types=1);

namespace App\Factory;

use App\Module\Entity\LogLineInterface;
use DateTimeInterface;

interface LogLineFactoryInterface
{
    public function get(string $fileLocation, string $serviceName, string $log, DateTimeInterface $date, DateTimeInterface $ingestionDate, int $statusCode): LogLineInterface;
}
