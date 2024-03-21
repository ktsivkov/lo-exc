<?php
declare(strict_types=1);

namespace App\Tests\Functional\Command;

use App\Entity\LogLine;
use App\Tests\Functional\MigrationsRequiredKernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class LogsAggregateCommandKernelTest extends MigrationsRequiredKernelTestCase
{
    public function testSuccessfulExecute(): void
    {
        self::bootKernel();
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $application = new Application(self::$kernel);
        $projectDir = $application->getKernel()->getProjectDir();
        $stubsDir = implode(DIRECTORY_SEPARATOR, [
            $projectDir,
            'tests',
            'Stubs',
        ]);
        $logFileLocation = implode(DIRECTORY_SEPARATOR, [$stubsDir, 'logs-stub.log']);

        // Run
        $commandTester = new CommandTester($application->find('logs:aggregate'));
        $commandTester->execute([
            'file' => $logFileLocation,
        ]);
        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Successfully processed 20 lines of file', $output);
        $this->assertStringContainsString($logFileLocation, $output);
        $this->assertEquals(20, $em->getRepository(LogLine::class)->count());
    }

    public function testTwoSuccessfulExecutesOneWithNoNewLines(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $projectDir = $application->getKernel()->getProjectDir();
        $stubsDir = implode(DIRECTORY_SEPARATOR, [
            $projectDir,
            'tests',
            'Stubs',
        ]);
        $logFileLocation = implode(DIRECTORY_SEPARATOR, [$stubsDir, 'logs-stub.log']);

        // First Run
        $commandTester = new CommandTester($application->find('logs:aggregate'));
        $commandTester->execute([
            'file' => $logFileLocation,
        ]);
        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Successfully processed 20 lines of file', $output);
        $this->assertStringContainsString($logFileLocation, $output);
        $this->assertEquals(20, $em->getRepository(LogLine::class)->count());

        // Second Run
        $commandTester = new CommandTester($application->find('logs:aggregate'));
        $commandTester->execute([
            'file' => $logFileLocation,
        ]);
        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('No new lines found in file', $output);
        $this->assertStringContainsString($logFileLocation, $output);
        $this->assertEquals(20, $em->getRepository(LogLine::class)->count());
    }

    public function testTwoConsecutiveSuccessfulExecutesWithBatchSize10(): void
    {
        self::bootKernel();
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $application = new Application(self::$kernel);
        $projectDir = $application->getKernel()->getProjectDir();
        $stubsDir = implode(DIRECTORY_SEPARATOR, [
            $projectDir,
            'tests',
            'Stubs',
        ]);
        $logFileLocation = implode(DIRECTORY_SEPARATOR, [$stubsDir, 'logs-stub.log']);

        // First run
        $firstExecuteCommandTester = new CommandTester($application->find('logs:aggregate'));
        $firstExecuteCommandTester->execute([
            'file' => $logFileLocation,
            '--batch-size' => 10,
        ]);
        $firstExecuteCommandTester->assertCommandIsSuccessful();
        $firstExecuteOutput = $firstExecuteCommandTester->getDisplay();
        $this->assertStringContainsString('Successfully processed 10 lines of file', $firstExecuteOutput);
        $this->assertStringContainsString($logFileLocation, $firstExecuteOutput);
        $this->assertEquals(10, $em->getRepository(LogLine::class)->count());

        // Second run
        $firstExecuteCommandTester = new CommandTester($application->find('logs:aggregate'));
        $firstExecuteCommandTester->execute([
            'file' => $logFileLocation,
            '--batch-size' => 10,
        ]);
        $firstExecuteCommandTester->assertCommandIsSuccessful();
        $firstExecuteOutput = $firstExecuteCommandTester->getDisplay();
        $this->assertStringContainsString('Successfully processed 10 lines of file', $firstExecuteOutput);
        $this->assertStringContainsString($logFileLocation, $firstExecuteOutput);
        $this->assertEquals(20, $em->getRepository(LogLine::class)->count());
    }

    public function testFailExecuteNonExistentFile(): void
    {
        self::bootKernel();
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $application = new Application(self::$kernel);
        $projectDir = $application->getKernel()->getProjectDir();
        $stubsDir = implode(DIRECTORY_SEPARATOR, [
            $projectDir,
            'tests',
            'Stubs',
        ]);
        $logFileLocation = implode(DIRECTORY_SEPARATOR, [$stubsDir, 'non-existent-file.log']);

        // Run
        $firstExecuteCommandTester = new CommandTester($application->find('logs:aggregate'));
        $firstExecuteCommandTester->execute([
            'file' => $logFileLocation,
        ]);
        $this->assertEquals(Command::INVALID, $firstExecuteCommandTester->getStatusCode(), 'Command expected to fail.');
        $firstExecuteOutput = $firstExecuteCommandTester->getDisplay();
        $this->assertStringContainsString('Error while processing file', $firstExecuteOutput);
        $this->assertStringContainsString($logFileLocation, $firstExecuteOutput);
        $this->assertEquals(0, $em->getRepository(LogLine::class)->count());
    }

    public function testFailGivenDirectoryNotFile(): void
    {
        self::bootKernel();
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $application = new Application(self::$kernel);
        $projectDir = $application->getKernel()->getProjectDir();
        $stubsDir = implode(DIRECTORY_SEPARATOR, [
            $projectDir,
            'tests',
            'Stubs',
        ]);
        // Run
        $firstExecuteCommandTester = new CommandTester($application->find('logs:aggregate'));
        $firstExecuteCommandTester->execute([
            'file' => $stubsDir,
        ]);
        $this->assertEquals(Command::INVALID, $firstExecuteCommandTester->getStatusCode(), 'Command expected to fail.');
        $firstExecuteOutput = $firstExecuteCommandTester->getDisplay();
        $this->assertStringContainsString('Error while processing file', $firstExecuteOutput);
        $this->assertStringContainsString($stubsDir, $firstExecuteOutput);
        $this->assertEquals(0, $em->getRepository(LogLine::class)->count());
    }

    public function testFailInvalidFile(): void
    {
        self::bootKernel();
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $application = new Application(self::$kernel);
        $projectDir = $application->getKernel()->getProjectDir();
        $stubsDir = implode(DIRECTORY_SEPARATOR, [
            $projectDir,
            'tests',
            'Stubs',
        ]);
        $logFileLocation = implode(DIRECTORY_SEPARATOR, [$stubsDir, 'invalid-logs-stub.log']);

        // Run
        $firstExecuteCommandTester = new CommandTester($application->find('logs:aggregate'));
        $firstExecuteCommandTester->execute([
            'file' => $logFileLocation,
        ]);
        $this->assertEquals(Command::FAILURE, $firstExecuteCommandTester->getStatusCode(), 'Command expected to fail.');
        $firstExecuteOutput = $firstExecuteCommandTester->getDisplay();
        $this->assertStringContainsString('Error while processing file', $firstExecuteOutput);
        $this->assertStringContainsString($logFileLocation, $firstExecuteOutput);
        $this->assertEquals(0, $em->getRepository(LogLine::class)->count());
    }
}
