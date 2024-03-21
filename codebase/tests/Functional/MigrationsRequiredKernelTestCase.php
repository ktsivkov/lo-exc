<?php
declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

abstract class MigrationsRequiredKernelTestCase extends KernelTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $migrationsCommand = new CommandTester($application->find('doctrine:database:drop'));
        $migrationsCommand->execute(['--force' => true], ['interactive' => false]);
        $migrationsCommand = new CommandTester($application->find('doctrine:database:create'));
        $migrationsCommand->execute([], ['interactive' => false]);
        $migrationsCommand = new CommandTester($application->find('doctrine:migrations:migrate'));
        $migrationsCommand->execute([], ['interactive' => false]);
        parent::setUp();
    }
}
