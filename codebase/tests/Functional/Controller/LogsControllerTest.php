<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\DataFixtures\AppFixtures;
use App\Request\CountRequest;
use App\Tests\Functional\MigrationsRequiredWebTestCase;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class LogsControllerTest extends MigrationsRequiredWebTestCase
{
    public function testCountAll(): void
    {
        $client = static::createClient();
        $this->runFixtures();

        $client->request('GET', '/count');
        $this->assertResponseIsSuccessful();
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(['counter' => 40], $actual);
    }

    public function testCountByStatusCode(): void
    {
        $client = static::createClient();
        $this->runFixtures();

        $client->request('GET', '/count', parameters: [
            'statusCode' => 200,
        ]);
        $this->assertResponseIsSuccessful();
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(['counter' => 20], $actual);
    }

    public function testCountWithInvalidStatusCode(): void
    {
        $client = static::createClient();
        $this->runFixtures();

        $client->request('GET', '/count', parameters: [
            'statusCode' => 'a string',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCountByServiceName(): void
    {
        $client = static::createClient();
        $this->runFixtures();

        $client->request('GET', '/count', parameters: [
            'serviceNames' => ['USERS-SERVICE'],
        ]);
        $this->assertResponseIsSuccessful();
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(['counter' => 20], $actual);
    }

    public function testCountWithInvalidServiceNames(): void
    {
        $client = static::createClient();
        $this->runFixtures();

        $client->request('GET', '/count', parameters: [
            'serviceNames' => 'a string',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCountByStartDate(): void
    {
        $client = static::createClient();
        $this->runFixtures();
        $fixtureAssumedDate = new DateTime(AppFixtures::START_DATE);

        $client->request('GET', '/count', parameters: [
            'startDate' => $fixtureAssumedDate->modify('+10 days')->modify('-1 hour')->format(CountRequest::DATE_TIME_FORMAT),
        ]);
        $this->assertResponseIsSuccessful();
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(['counter' => 20], $actual);
    }

    public function testCountWithInvalidStartDate(): void
    {
        $client = static::createClient();
        $this->runFixtures();

        $client->request('GET', '/count', parameters: [
            'startDate' => 'a string',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCountByEndDate(): void
    {
        $client = static::createClient();
        $this->runFixtures();
        $fixtureAssumedDate = new DateTime(AppFixtures::START_DATE);

        $client->request('GET', '/count', parameters: [
            'endDate' => $fixtureAssumedDate->modify('+10 days')->modify('-1 hour')->format(CountRequest::DATE_TIME_FORMAT),
        ]);
        $this->assertResponseIsSuccessful();
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(['counter' => 20], $actual);
    }

    public function testCountWithInvalidEndDate(): void
    {
        $client = static::createClient();
        $this->runFixtures();

        $client->request('GET', '/count', parameters: [
            'endDate' => 'a string',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCountWithInvalidStartDateAfterEndDate(): void
    {
        $client = static::createClient();
        $this->runFixtures();
        $fixtureAssumedDate1 = new DateTime(AppFixtures::START_DATE);
        $fixtureAssumedDate2 = new DateTime(AppFixtures::START_DATE);

        $client->request('GET', '/count', parameters: [
            'startDate' => $fixtureAssumedDate1->modify('+10 days')->modify('-1 hour')->format(CountRequest::DATE_TIME_FORMAT),
            'endDate' => $fixtureAssumedDate2->modify('+9 days')->modify('-1 hour')->format(CountRequest::DATE_TIME_FORMAT),
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}