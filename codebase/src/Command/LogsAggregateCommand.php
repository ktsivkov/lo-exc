<?php
declare(strict_types=1);

namespace App\Command;

use App\Module\File\Service\Exception\FileValidationException;
use App\Module\File\Service\FileOffsetServiceInterface;
use App\Module\File\Service\FileReaderServiceInterface;
use App\Module\Log\Service\Exception\LinesProcessingException;
use App\Module\Log\Service\LogLinesProcessorServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'logs:aggregate',
    description: 'Log file aggregation',
)]
class LogsAggregateCommand extends Command
{
    private const FILE_ARG = 'file';
    private const BATCH_SIZE_OPT = 'batch-size';
    private const BATCH_SIZE_OPT_SHORTCUT = 'b';
    private const BATCH_SIZE_OPT_DEFAULT_VAL = 1000;
    private const FILE_VALIDATION_MESSAGE = 'Error while processing file `%s`: %s';
    private const NO_NEW_LINES_MESSAGE = 'No new lines found in file `%s`.';
    private const COULD_NOT_PROCESS_LINE_MESSAGE = 'Error while processing file `%s` line %s``: %s';
    private const FILE_LOCATION_LOG_CTX = 'file_location';
    private const LOG_LINE_LOG_CTX = 'log_line';
    private const SUCCESSFUL_PROCESS_LOG = 'Successfully processed %d lines of file `%s`.';

    public function __construct(
        private readonly FileReaderServiceInterface        $fileReaderService,
        private readonly LogLinesProcessorServiceInterface $linesProcessorService,
        private readonly FileOffsetServiceInterface        $fileOffsetService,
        private readonly LoggerInterface                   $logger,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(name: self::FILE_ARG, mode: InputArgument::REQUIRED, description: 'Target file to ingest')
            ->addOption(name: self::BATCH_SIZE_OPT, shortcut: self::BATCH_SIZE_OPT_SHORTCUT, mode: InputOption::VALUE_OPTIONAL, description: 'Limit of lines to be read', default: self::BATCH_SIZE_OPT_DEFAULT_VAL);
    }

    // !!!NOTE: This function could be running continuously, but if anything goes wrong it could easily introduce memory leaks.
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle(input: $input, output: $output);
        $fileLocation = $input->getArgument(name: self::FILE_ARG);
        $batchSize = (int)$input->getOption(name: self::BATCH_SIZE_OPT); // TODO: I'd consider validating that the option is an integer in general

        try {
            $offset = $this->fileOffsetService->getFileOffset(fileLocation: $fileLocation);
            $linesResult = $this->fileReaderService->readLines(fileLocation: $fileLocation, batchSize: $batchSize, offset: $offset);
            if (!$linesResult->lines) {
                $io->note(sprintf(self::NO_NEW_LINES_MESSAGE, $fileLocation));
                return Command::SUCCESS;
            }
        } catch (FileValidationException $e) {
            $msg = sprintf(self::FILE_VALIDATION_MESSAGE, $e->getFileLocation(), $e->getMessage());
            $io->error($msg);
            $this->logger->error($msg, [
                self::FILE_LOCATION_LOG_CTX => $e->getFileLocation(),
            ]);
            return Command::INVALID; // Maybe FAILURE makes more sense?
        }

        try {
            $this->linesProcessorService->processLines(
                fileLocation: $fileLocation,
                lines: $linesResult->lines,
            );
        } catch (LinesProcessingException $e) {
            $msg = sprintf(self::COULD_NOT_PROCESS_LINE_MESSAGE, $e->getFileLocation(), $e->getLogLine(), $e->getMessage());
            $io->warning($msg);
            $this->logger->error($msg, [
                self::FILE_LOCATION_LOG_CTX => $e->getFileLocation(),
                self::LOG_LINE_LOG_CTX => $e->getLogLine(),
            ]);
            return Command::FAILURE;
        }

        $this->fileOffsetService->saveFileOffset(fileLocation: $fileLocation, offset: $linesResult->offset);

        $successMsg = sprintf(self::SUCCESSFUL_PROCESS_LOG, count($linesResult->lines), $linesResult->fileLocation);
        $io->success($successMsg);
        $this->logger->info($successMsg);

        return Command::SUCCESS;
    }
}
