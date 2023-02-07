<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Console\Application;

ini_set('max_execution_time', '0');

/** @var ServiceManager $container */
$container = require __DIR__ . '/../config/container.php';

$em = $container->get(EntityManager::class);

// Doctrine Migration Dependencies
$dependencyFactory = DependencyFactory::fromEntityManager(
    new ExistingConfiguration($container->get(Configuration::class)),
    new ExistingEntityManager($em)
);

// add doctrine commands
$application = new Application('Release Bus CLI');
$application->setHelperSet(ConsoleRunner::createHelperSet($em));
ConsoleRunner::addCommands($application);
\Doctrine\Migrations\Tools\Console\ConsoleRunner::addCommands($application, $dependencyFactory);
$config = (array) ($container->get('config') ?? []);
$commands = (array) ($config['console']['commands'] ?? []);

foreach ($commands as $command) {
    $application->add($container->get($command));
}

$application->run();
