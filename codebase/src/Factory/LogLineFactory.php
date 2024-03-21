<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\LogLine;
use App\Module\Entity\LogLineInterface;
use DateTimeInterface;

readonly class LogLineFactory implements LogLineFactoryInterface
{
    public function get(
        string            $fileLocation,
        string            $serviceName,
        string            $log,
        DateTimeInterface $date,
        DateTimeInterface $ingestionDate,
        int               $statusCode
    ): LogLineInterface
    {
        $logLine = new LogLine();
        $logLine->setLogFile($fileLocation);
        $logLine->setServiceName($serviceName);
        $logLine->setLog($log);
        $logLine->setDate($date);
        $logLine->setIngestionDate($ingestionDate);
        $logLine->setStatusCode($statusCode);
        return $logLine;
    }
}
