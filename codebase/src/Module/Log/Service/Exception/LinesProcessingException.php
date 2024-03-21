<?php
declare(strict_types=1);

namespace App\Module\Log\Service\Exception;

use Throwable;

interface LinesProcessingException extends Throwable
{
    public function getFileLocation(): string;

    public function getLogLine(): string;
}
