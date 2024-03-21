<?php
declare(strict_types=1);

namespace App\Request\Exception;

use Throwable;

interface InvalidParameter extends Throwable
{
    public function getParameter(): string;

    public function getValue(): mixed;
}