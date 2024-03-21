<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\LogLineFactoryInterface;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const START_DATE = '2020-01-01';

    public function __construct(private readonly LogLineFactoryInterface $logLineFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $date = (new DateTime(self::START_DATE))->modify(sprintf('+%d days', $i));
            $statusCode = $i % 2 === 0 ? 200 : 404;
            $manager->persist($this->logLineFactory->get(
                fileLocation: 'random-log-file',
                serviceName: 'USERS-SERVICE',
                log: 'SOME LOG DATA',
                date: $date,
                ingestionDate: $date,
                statusCode: $statusCode,
            ));
            $manager->persist($this->logLineFactory->get(
                fileLocation: 'random-log-file',
                serviceName: 'INVOICE-SERVICE',
                log: 'SOME LOG DATA',
                date: $date,
                ingestionDate: $date,
                statusCode: $statusCode,
            ));
        }

        $manager->flush();
    }
}
