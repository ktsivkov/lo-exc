<?php
declare(strict_types=1);

namespace App\Factory;

use DateTimeInterface;

interface DateTimeFactoryInterface
{
    public function getNow(): DateTimeInterface;
}
