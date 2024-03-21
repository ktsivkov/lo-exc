<?php
declare(strict_types=1);

namespace App\Factory;

use App\Request\CountRequest;
use App\Request\Exception\InvalidQueryParameterException;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\Request;

class CountRequestFactory implements CountRequestFactoryInterface
{
    const SERVICE_NAMES = 'serviceNames';
    const START_DATE = 'startDate';
    const END_DATE = 'endDate';
    const STATUS_CODE = 'statusCode';

    /**
     * {@inheritDoc}
     */
    public function get(Request $request): CountRequest
    {
        $serviceNames = $request->get(self::SERVICE_NAMES);
        $statusCode = $request->get(self::STATUS_CODE);
        $startDate = $this->getDateTime($request->get(self::START_DATE), self::START_DATE);
        $endDate = $this->getDateTime($request->get(self::END_DATE), self::END_DATE);

        if ($serviceNames) {
            if (!is_array($serviceNames)) {
                throw new
                InvalidQueryParameterException(parameter: self::SERVICE_NAMES, value: $serviceNames, message: 'array expected');
            }
            foreach ($serviceNames as $i => $serviceName) {
                if (!is_string($serviceName)) {
                    throw new InvalidQueryParameterException(parameter: sprintf('%s[%d]', self::SERVICE_NAMES, $i), value: $serviceName, message: 'should be string');
                }
            }
        }

        if ($statusCode && !is_numeric($statusCode)) {
            throw new InvalidQueryParameterException(parameter: self::STATUS_CODE, value: $statusCode, message: 'int expected');
        }
        $statusCode = (int)$statusCode;

        if ($startDate && $endDate && $startDate->getTimestamp() - $endDate->getTimestamp() > 0) {
            throw new InvalidQueryParameterException(parameter: self::START_DATE, value: $request->get(self::START_DATE), message: 'should be before endDate');
        }


        return new CountRequest(
            serviceNames: $serviceNames,
            startDate: $startDate,
            endDate: $endDate,
            statusCode: $statusCode,
        );
    }

    /**
     * @throws InvalidQueryParameterException
     */
    private function getDateTime(mixed $value, string $name): ?DateTimeInterface
    {
        if (!$value) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidQueryParameterException(parameter: $name, value: $value, message: sprintf('should be in format `%s`', CountRequest::DATE_TIME_FORMAT));
        }

        $date = date_create_from_format(CountRequest::DATE_TIME_FORMAT, $value);
        if ($date === false) {
            throw new InvalidQueryParameterException(parameter: $name, value: $value, message: sprintf('should be in format `%s`', CountRequest::DATE_TIME_FORMAT));
        }

        return $date;
    }
}
