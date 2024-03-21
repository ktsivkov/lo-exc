<?php
declare(strict_types=1);

namespace App\Factory;

use DateTime;
use DateTimeInterface;

readonly class DateTimeFactory implements DateTimeFactoryInterface
{
    public function getNow(): DateTimeInterface
    {
        return new DateTime(datetime: 'now');
    }
}
