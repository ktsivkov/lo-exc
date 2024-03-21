<?php

namespace App\Factory;

use App\Request\CountRequest;
use App\Request\Exception\InvalidParameter;
use Symfony\Component\HttpFoundation\Request;

interface CountRequestFactoryInterface
{
    /**
     * @param Request $request
     * @return CountRequest
     * @throws InvalidParameter
     */
    public function get(Request $request): CountRequest;
}
