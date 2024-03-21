<?php
declare(strict_types=1);

namespace App\Module\Log\Service;

use App\Entity\LogLine;
use App\Factory\DateTimeFactoryInterface;
use App\Factory\LogLineFactoryInterface;
use App\Module\Log\Repository\LogLineRepositoryInterface;
use App\Module\Log\Service\Exception\InvalidLineException;

readonly class LogLinesProcessorService implements LogLinesProcessorServiceInterface
{
    const LOG_DATE_FORMAT = 'd/M/Y:H:i:s O';

    public function __construct(private LogLineRepositoryInterface $lineRepository, private DateTimeFactoryInterface $dateTimeFactory, private LogLineFactoryInterface $logLineFactory)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function processLines(string $fileLocation, array $lines): void
    {
        $lines = array_map(
            fn(string $line): LogLine => $this->getLogLine(fileLocation: $fileLocation, logLine: $line),
            $lines
        );
        $this->lineRepository->saveLines($lines);
    }

    /**
     * @throws InvalidLineException
     */
    private function getLogLine(string $fileLocation, string $logLine): LogLine
    {
        $pattern = '/^(\S+) - - \[(.*)\] "(.+)" (\d+)/';
        if (preg_match($pattern, $logLine, $matches)) {
            return $this->logLineFactory->get(
                fileLocation: $fileLocation,
                serviceName: trim($matches[1]),
                log: $matches[3],
                date: date_create_from_format(self::LOG_DATE_FORMAT, $matches[2]),
                ingestionDate: $this->dateTimeFactory->getNow(),
                statusCode: intval($matches[4]),
            );
        }
        throw new InvalidLineException(fileLocation: $fileLocation, logLine: $logLine);
    }
}
