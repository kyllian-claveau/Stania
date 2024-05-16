<?php

namespace App\Business;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabaseBusiness
{
    public function __construct(
        private readonly KernelInterface        $kernel,
        private readonly EntityManagerInterface $entityManager,
        private readonly DependencyFactory      $dependencyFactory,
        private readonly ParameterBagInterface  $parameterBag,
        private readonly Connection             $withoutDatabaseConnection,
    )
    {
    }

    public function getMissingMigrations(): array
    {
        $resultSetMapping = new ResultSetMapping();
        $resultSetMapping->addScalarResult('version', 'version');
        try {
            $results = $this->entityManager->createNativeQuery('SELECT version FROM doctrine_migration_versions', $resultSetMapping)->getArrayResult();
        } catch (TableNotFoundException|ConnectionException) {
            $results = [];
        }
        $migrations = $this->dependencyFactory->getMigrationRepository()->getMigrations();
        $missingMigrations = [];
        foreach ($migrations->getItems() as $migration) {
            foreach ($results as $result) {
                if ((string)$migration->getVersion() === $result['version']) {
                    continue 2;
                }
            }
            $missingMigrations[] = $migration->getVersion();
        }

        return $missingMigrations;
    }

    public function upToDate(): bool
    {
        if (!$this->doesDatabaseExist()) {
            return false;
        }

        if (!empty($this->getMissingMigrations())) {
            return false;
        }

        return true;
    }

    public function update(): void
    {
        if ($this->upToDate()) {
            return;
        }

        if (!$this->doesDatabaseExist()) {
            $this->createDatabase();
        }

        if (!empty($this->getMissingMigrations())) {
            $this->migrate();
        }
    }

    public function canConnectToDatabase(): bool
    {
        try {
            $this->withoutDatabaseConnection->connect();
        } catch (ConnectionException) {
            return false;
        }

        return true;
    }

    public function doesDatabaseExist(): bool
    {
        try {
            $this->entityManager->getConnection()->connect();
        } catch (ConnectionException) {
            return false;
        }

        return true;
    }

    public function migrate(): void
    {
        $this->executeCommand(new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true,
        ]));
    }

    public function createDatabase(): void
    {
        $this->executeCommand(new ArrayInput([
            'command' => 'doctrine:database:create',
            '--if-not-exists' => true,
        ]));
    }

    private function executeCommand(InputInterface $input): void
    {

        $application = new Application($this->kernel);
        $application->setAutoExit(false);


        $output = new NullOutput();
        $application->run($input, $output);
    }

    public function getDatabaseName(): string
    {
        return $this->parameterBag->get('database_name');
    }
}