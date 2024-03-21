<?php
declare(strict_types=1);

namespace App\Module\File\Service\Exception;

use Throwable;

interface FileValidationException extends Throwable
{
    public function getFileLocation(): string;
}
