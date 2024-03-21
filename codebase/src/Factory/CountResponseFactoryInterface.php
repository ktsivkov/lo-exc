<?php
declare(strict_types=1);

namespace App\Factory;

use App\Response\CountResponse;

interface CountResponseFactoryInterface
{
    public function get(int $counter): CountResponse;
}
